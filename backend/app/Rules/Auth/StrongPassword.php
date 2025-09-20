<?php

namespace App\Rules\Auth;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    protected $minLength = 8;
    protected $requiresUppercase = true;
    protected $requiresLowercase = true;
    protected $requiresNumeric = true;
    protected $requiresSpecialChar = true;

    /**
     * Create a new rule instance.
     *
     * @param int $minLength
     * @param bool $requiresUppercase
     * @param bool $requiresLowercase
     * @param bool $requiresNumeric
     * @param bool $requiresSpecialChar
     */
    public function __construct(
        int $minLength = 8,
        bool $requiresUppercase = true,
        bool $requiresLowercase = true,
        bool $requiresNumeric = true,
        bool $requiresSpecialChar = true
    ) {
        $this->minLength = $minLength;
        $this->requiresUppercase = $requiresUppercase;
        $this->requiresLowercase = $requiresLowercase;
        $this->requiresNumeric = $requiresNumeric;
        $this->requiresSpecialChar = $requiresSpecialChar;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) < $this->minLength) {
            return false;
        }

        if ($this->requiresUppercase && !preg_match('/[A-Z]/', $value)) {
            return false;
        }

        if ($this->requiresLowercase && !preg_match('/[a-z]/', $value)) {
            return false;
        }

        if ($this->requiresNumeric && !preg_match('/[0-9]/', $value)) {
            return false;
        }

        if ($this->requiresSpecialChar && !preg_match('/[^A-Za-z0-9]/', $value)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'كلمة المرور يجب أن تحتوي على الأقل على ' . $this->minLength . ' أحرف';

        $requirements = [];
        
        if ($this->requiresUppercase) {
            $requirements[] = 'حرف كبير واحد على الأقل (A-Z)';
        }
        
        if ($this->requiresLowercase) {
            $requirements[] = 'حرف صغير واحد على الأقل (a-z)';
        }
        
        if ($this->requiresNumeric) {
            $requirements[] = 'رقم واحد على الأقل (0-9)';
        }
        
        if ($this->requiresSpecialChar) {
            $requirements[] = 'رمز خاص واحد على الأقل (!@#$%^&* etc.)';
        }

        if (!empty($requirements)) {
            $message .= ' وتشمل ' . implode('، ', $requirements);
        }

        return $message . '.';
    }

    /**
     * Set custom minimum length.
     *
     * @param int $length
     * @return $this
     */
    public function minLength(int $length)
    {
        $this->minLength = $length;
        return $this;
    }

    /**
     * Set whether to require uppercase letters.
     *
     * @param bool $require
     * @return $this
     */
    public function requireUppercase(bool $require = true)
    {
        $this->requiresUppercase = $require;
        return $this;
    }

    /**
     * Set whether to require lowercase letters.
     *
     * @param bool $require
     * @return $this
     */
    public function requireLowercase(bool $require = true)
    {
        $this->requiresLowercase = $require;
        return $this;
    }

    /**
     * Set whether to require numeric characters.
     *
     * @param bool $require
     * @return $this
     */
    public function requireNumeric(bool $require = true)
    {
        $this->requiresNumeric = $require;
        return $this;
    }

    /**
     * Set whether to require special characters.
     *
     * @param bool $require
     * @return $this
     */
    public function requireSpecialChar(bool $require = true)
    {
        $this->requiresSpecialChar = $require;
        return $this;
    }
}