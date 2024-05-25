<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'country',
        'city',
        'state',
        'zip_code',
        'phone',
        'email',
        'user_id',
        'company_type_id',
        'location',
        'logo_path',
        'radius',
        'banner_path',
        'popular',
        'delivery_cost',
        'is_approved',
        'edit_duplicate_id'
    ];

    protected $hidden = ['location'];

    public static function boot()
    {
        parent::boot();

        // Generating location on creating and updating events
        static::creating(function ($company) {
            $company->setLocation();
        });

        static::updating(function ($company) {
            $company->setLocation();
        });
    }

    public function setLocation()
    {
        if (!$this->address || !$this->zip_code || !$this->country) {
            return;
        }

        $fullAddress = "{$this->address}, {$this->zip_code}, {$this->country}";

        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($fullAddress) . "&key=" . $apiKey;

        $response = Http::get($url);

        if ($response->ok()) {
            $locationData = $response->json()['results'][0]['geometry']['location'];

            $latitude = $locationData['lat'];
            $longitude = $locationData['lng'];

            $wktLocation = "POINT({$longitude} {$latitude})";

            $this->location = DB::raw("ST_GeomFromText('{$wktLocation}')");
        } else {
            // handle the error
        }
    }

    public function typeIsGroup()
    {
        return $this->companyType->group;
    }

    public function categories()
    {
        return $this->belongsToMany(CompanyCategory::class, 'category_company', 'company_id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productCategories()
    {
        return $this->belongsToMany(ProductCategory::class);
    }

    public function fulfillmentTypes()
    {
        return $this->belongsToMany(FulfillmentType::class, 'company_fulfillment_type', 'company_id', 'fulfillment_type_id');
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reservations()
    {
        return $this->slots()->with('reservation')->get()
            ->pluck('reservation')
            ->whereNotNull();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function companyType()
    {
        return $this->belongsTo(CompanyType::class);
    }

    public function openingTimes()
    {
        return $this->hasMany(OpeningTime::class);
    }

    public function getLogoPathAttribute($value)
    {
        if ($value) {
            return env("MEDIA_URL") . $value;
        }

        return $value;
    }

    public function getBannerPathAttribute($value)
    {
        if ($value) {
            return env("MEDIA_URL") . $value;
        }

        return $value;
    }

    public function getRevenueAttribute()
    {
        return $this->orders()
            ->where('paid', true)
            ->selectRaw('sum(price) as sum, YEAR(created_at) as year, MONTH(created_at) as month, WEEK(created_at) as week')
            ->groupBy('year', 'month', 'week')
            ->orderBy('year', 'desc')
            ->get();
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'company_likes', 'company_id', 'user_id');
    }

    public function calculateDeliveryTime($distance = null, $latitude = null, $longitude = null)
    {
        if ($distance === null && $latitude !== null && $longitude !== null) {
            // Calculate distance if not provided
            $distance = DB::select(DB::raw("SELECT ROUND(ST_Distance_Sphere(location, ST_GeomFromText('POINT({$longitude} {$latitude})')) / 1000, 1) as distance"))[0]->distance;
        }

        $averageScooterSpeed = 30; // km/h
        $averageRestaurantPrepareTime = 15; // minutes

        if ($distance !== null) {
            $deliveryTime = ceil(($distance / $averageScooterSpeed) * 60); // Convert hours to minutes
            $roundedDeliveryTime = ceil($deliveryTime / 10) * 10; // Round to the nearest 10 minutes
            return $roundedDeliveryTime + $averageRestaurantPrepareTime;
        }

        return 0; // Return 0 or an appropriate value if distance cannot be calculated
    }

    public function calculatePickupTime()
    {
        $averageRestaurantPrepareTime = 15; // minutes

        // Add a buffer time for customer arrival variability
        $customerArrivalBuffer = 5; // minutes

        // Calculate total pickup time as preparation time plus customer arrival buffer
        $totalPickupTime = $averageRestaurantPrepareTime + $customerArrivalBuffer;

        return $totalPickupTime; // Return the total time in minutes
    }
}
