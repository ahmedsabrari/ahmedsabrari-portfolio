<?php

namespace App\Policies\Auth;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // فقط المسؤولون يمكنهم عرض قائمة المستخدمين
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // المستخدم يمكنه عرض ملفه الشخصي، والمسؤولون يمكنهم عرض أي مستخدم
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // فقط المسؤولون يمكنهم إنشاء مستخدمين جدد
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // المستخدم يمكنه تحديث ملفه الشخصي، والمسؤولون يمكنهم تحديث أي مستخدم
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // لا يمكن للمستخدم حذف نفسه، ولا يمكن حذف المسؤول الرئيسي
        if ($user->id === $model->id || $model->email === 'admin@gmail.com') {
            return false;
        }
        
        // فقط المسؤولون يمكنهم حذف المستخدمين
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // فقط المسؤولون يمكنهم استعادة المستخدمين المحذوفين
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // لا يمكن حذف المسؤول الرئيسي بشكل دائم
        if ($model->email === 'admin@gmail.com') {
            return false;
        }
        
        // فقط المسؤولون يمكنهم الحذف الدائم للمستخدمين
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can block the model.
     */
    public function block(User $user, User $model): bool
    {
        // لا يمكن للمستخدم حظر نفسه، ولا يمكن حظر المسؤول الرئيسي
        if ($user->id === $model->id || $model->email === 'admin@gmail.com') {
            return false;
        }
        
        // فقط المسؤولون يمكنهم حظر المستخدمين
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can unblock the model.
     */
    public function unblock(User $user, User $model): bool
    {
        // فقط المسؤولون يمكنهم فك حظر المستخدمين
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can change user role.
     */
    public function changeRole(User $user, User $model): bool
    {
        // لا يمكن للمستخدم تغيير دوره بنفسه، ولا يمكن تغيير دور المسؤول الرئيسي
        if ($user->id === $model->id || $model->email === 'admin@gmail.com') {
            return false;
        }
        
        // فقط المسؤولون يمكنهم تغيير أدوار المستخدمين
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view user statistics.
     */
    public function viewStats(User $user): bool
    {
        // فقط المسؤولون يمكنهم عرض إحصائيات المستخدمين
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage user permissions.
     */
    public function managePermissions(User $user, User $model): bool
    {
        // لا يمكن للمستخدم إدارة صلاحيات نفسه، ولا يمكن إدارة صلاحيات المسؤول الرئيسي
        if ($user->id === $model->id || $model->email === 'admin@gmail.com') {
            return false;
        }
        
        // فقط المسؤولون يمكنهم إدارة صلاحيات المستخدمين
        return $user->isAdmin();
    }
}