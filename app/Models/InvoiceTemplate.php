<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'name',
        'primary_color',
        'secondary_color',
        'font',
        'logo_position',
        'show_organization_logo',
        'show_project_logo',
        'footer_text',
        'additional_information',
        'has_watermark',
        'watermark_text',
        'has_signature',
        'signature_image',
        'signature_position',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'show_organization_logo' => 'boolean',
        'show_project_logo' => 'boolean',
        'has_watermark' => 'boolean',
        'has_signature' => 'boolean',
    ];

    /**
     * Get the organization that owns the invoice template.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the invoices for the invoice template.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'template_id');
    }
}