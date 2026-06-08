<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    protected $fillable = ['name','email','password','avatar','phone','date_naissance','genre','is_active'];
    protected $hidden = ['password','remember_token'];
    protected function casts(): array {
        return ['email_verified_at'=>'datetime','password'=>'hashed','is_active'=>'boolean','date_naissance'=>'date'];
    }
    public function professeur() { return $this->hasOne(Professeur::class); }
    public function etudiant() { return $this->hasOne(Etudiant::class); }
    public function getAvatarUrlAttribute(): string {
        if ($this->avatar) return asset('storage/'.$this->avatar);
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=6366f1&color=fff&bold=true';
    }
    public function getInitialsAttribute(): string {
        $parts = explode(' ', trim($this->name));
        $i = strtoupper(substr($parts[0],0,1));
        if (isset($parts[1])) $i .= strtoupper(substr($parts[1],0,1));
        return $i;
    }
    public function isAdmin(): bool { return $this->hasRole('admin'); }
    public function isProfesseur(): bool { return $this->hasRole('professeur'); }
    public function isEtudiant(): bool { return $this->hasRole('etudiant'); }
}
