<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use App\Model\AdminGroup;
/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $remark
 * @property string $realname
 * @property int $structure_id
 * @property int $post_id
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password', 'remark', 'realname', 'structure_id', 'post_id', 'status', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'structure_id' => 'integer', 'post_id' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    
    public function groups()
    {
        return $this->belongsToMany(AdminGroup::class, 'admin_access', 'user_id', 'group_id');
    }
}