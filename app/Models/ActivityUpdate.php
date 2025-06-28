<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityUpdate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'activity_id',
        'updated_by',
        'previous_status',
        'new_status',
        'remark',
        'user_bio_details',
        'update_time',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'update_time' => 'datetime',
            'user_bio_details' => 'array',
        ];
    }

    /**
     * Get the activity this update belongs to.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the user who made this update.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Boot the model and set default values.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activityUpdate) {
            if (empty($activityUpdate->update_time)) {
                $activityUpdate->update_time = now();
            }
        });
    }

    /**
     * Get status change description.
     */
    public function getStatusChangeDescription(): string
    {
        if ($this->previous_status === null) {
            return "Status set to: {$this->new_status}";
        }

        return "Status changed from {$this->previous_status} to {$this->new_status}";
    }

    /**
     * Get formatted update time.
     */
    public function getFormattedUpdateTime(): string
    {
        return $this->update_time->format('M d, Y g:i A');
    }

    /**
     * Get user name from bio details.
     */
    public function getUserName(): string
    {
        return $this->user_bio_details['name'] ?? 'Unknown User';
    }

    /**
     * Get user employee ID from bio details.
     */
    public function getUserEmployeeId(): string
    {
        return $this->user_bio_details['employee_id'] ?? 'N/A';
    }

    /**
     * Get user department from bio details.
     */
    public function getUserDepartment(): string
    {
        $userDepartmentId = $this->user_bio_details['department_id'] ?? 'N/A';
        $userDepartment = Department::find($userDepartmentId);
        return $userDepartment ? $userDepartment->name : 'N/A';
    }
}
