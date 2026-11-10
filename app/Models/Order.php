<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'payment_status',
        'shipping_status',
        'currency',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'shipping_fee',
        'tax_amount',
        'total_amount',
        'shipping_method_id',
        'shipping_address',
        'billing_address',
        'customer_email',
        'customer_phone',
        'customer_notes',
        'admin_notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'shipping_address' => 'array',
            'billing_address' => 'array',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->shipping_address['full_name'] ?? $this->user?->name ?? 'Distinguished Patron';
    }

    public function getShippingAddressLine1Attribute(): string
    {
        return $this->shipping_address['address_line_1'] ?? '';
    }

    public function getShippingAddressLine2Attribute(): ?string
    {
        return $this->shipping_address['address_line_2'] ?? null;
    }

    public function getShippingCityAttribute(): string
    {
        return $this->shipping_address['city'] ?? '';
    }

    public function getShippingStateAttribute(): ?string
    {
        return $this->shipping_address['state'] ?? null;
    }

    public function getShippingPostalCodeAttribute(): string
    {
        return $this->shipping_address['postal_code'] ?? '';
    }

    public function getShippingCountryAttribute(): string
    {
        return $this->shipping_address['country'] ?? '';
    }

    public function getPaymentMethodAttribute(): ?string
    {
        return $this->payments()->latest()->first()?->payment_method;
    }

    public static function generateOrderNumber(): string
    {
        return 'TVA-'.strtoupper(date('Ymd')).'-'.strtoupper(substr(uniqid(), -5));
    }
}
