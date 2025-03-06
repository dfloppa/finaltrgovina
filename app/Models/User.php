<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'preferred_payment_method_id',
        'preferred_shipping_method_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user's cart
     */
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the user's cart or create a new one if it doesn't exist
     * 
     * @return \App\Models\Cart
     */
    public function getOrCreateCart()
    {
        $cart = $this->cart()->where('completed', false)->latest()->first();
        
        if (!$cart) {
            $cart = $this->cart()->create([
                'total_price' => 0,
                'total_items' => 0,
                'completed' => false
            ]);
        }
        
        return $cart;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
    
    /**
     * Get the user's addresses.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }
    
    /**
     * Get the user's default billing address.
     */
    public function defaultBillingAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class)
            ->where('type', 'billing')
            ->where('is_default', true);
    }
    
    /**
     * Get the user's default shipping address.
     */
    public function defaultShippingAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class)
            ->where('type', 'shipping')
            ->where('is_default', true);
    }
    
    /**
     * Get the user's preferred payment method.
     */
    public function preferredPaymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'preferred_payment_method_id');
    }
    
    /**
     * Get the user's preferred shipping method.
     */
    public function preferredShippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'preferred_shipping_method_id');
    }
}
