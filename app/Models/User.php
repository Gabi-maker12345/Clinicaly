<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    public const ROLE_PATIENT = 'pacient';
    public const ROLE_DOCTOR = 'medico';
    public const ROLE_CLINIC = 'clinica';

    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'clinic_id',
        'specialty',
        'phone',
        'activity_hours',
        'is_available',
        'password',
        'profile_photo_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_available' => 'boolean',
        ];
    }

    public function searchHistories() {
        return $this->hasMany(SearchHistory::class);
    }

    public function chatLogs() {
        return $this->hasMany(ChatLog::class);
    }

    public function chatSessions()
    {
        return $this->hasMany(ChatSession::class);
    }

    public function conversations() {
        return $this->hasMany(Conversation::class, 'sender_id')->orWhere('receiver_id', $this->id);
    }

    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function (): string {
            if (! $this->profile_photo_path) {
                return $this->defaultProfilePhotoUrl();
            }

            $path = ltrim(str_replace('\\', '/', $this->profile_photo_path), '/');

            return '/storage/'.$path;
        });
    }

    public function patientAppointments()
    {
        return $this->hasMany(AppointmentRequest::class, 'patient_id');
    }

    public function doctorAppointments()
    {
        return $this->hasMany(AppointmentRequest::class, 'doctor_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(self::class, 'clinic_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(self::class, 'clinic_id');
    }

    public function clinicStockItems(): HasMany
    {
        return $this->hasMany(ClinicStockItem::class, 'clinic_id');
    }

    public function clinicAppointments(): HasMany
    {
        return $this->hasMany(AppointmentRequest::class, 'clinic_id');
    }

    public function clinicDiagnosticos(): HasMany
    {
        return $this->hasMany(Diagnostico::class, 'clinic_id');
    }

    public static function accountRoles(): array
    {
        return [
            self::ROLE_PATIENT => 'Paciente',
            self::ROLE_DOCTOR => 'Médico',
            self::ROLE_CLINIC => 'Clínica',
        ];
    }

    public function isDoctor(): bool
    {
        return in_array($this->normalizedRole(), [self::ROLE_DOCTOR, 'doctor', 'médico'], true);
    }

    public function isPatient(): bool
    {
        return in_array($this->normalizedRole(), [self::ROLE_PATIENT, 'patient', 'paciente'], true);
    }

    public function isClinic(): bool
    {
        return in_array($this->normalizedRole(), [self::ROLE_CLINIC, 'clinic', 'clínica'], true);
    }

    public function roleLabel(): string
    {
        if ($this->isDoctor()) {
            return 'Médico';
        }

        if ($this->isClinic()) {
            return 'Clínica';
        }

        return 'Paciente';
    }

    public function clinicOwnerId(): ?int
    {
        return $this->isClinic() ? $this->id : $this->clinic_id;
    }

    private function normalizedRole(): string
    {
        return mb_strtolower(trim((string) $this->role));
    }

    public function isOpenNow(?\DateTimeInterface $date = null): bool
    {
        if (! $this->isClinic()) {
            return false;
        }

        $hours = trim((string) $this->activity_hours);

        if ($hours === '') {
            return false;
        }

        if (preg_match('/(\d{1,2})(?::?(\d{2}))?\s*(?:h)?\s*[-–]\s*(\d{1,2})(?::?(\d{2}))?/i', $hours, $matches) !== 1) {
            return false;
        }

        $start = ((int) $matches[1]) * 60 + (int) ($matches[2] ?? 0);
        $end = ((int) $matches[3]) * 60 + (int) ($matches[4] ?? 0);
        $now = $date ? (int) $date->format('H') * 60 + (int) $date->format('i') : ((int) now()->format('H') * 60 + (int) now()->format('i'));

        if ($start === $end) {
            return true;
        }

        if ($start < $end) {
            return $now >= $start && $now <= $end;
        }

        return $now >= $start || $now <= $end;
    }
}
