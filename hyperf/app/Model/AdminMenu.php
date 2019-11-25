<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id
 * @property int $pid
 * @property string $title
 * @property string $url
 * @property string $icon
 * @property int $menu_type
 * @property int $sort
 * @property int $status
 * @property int $rule_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pid', 'title', 'url', 'icon', 'menu_type', 'sort', 'status', 'rule_id', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'menu_type' => 'integer', 'sort' => 'integer', 'status' => 'integer', 'rule_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}