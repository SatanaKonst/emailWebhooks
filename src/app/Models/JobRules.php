<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRules extends Model
{
    use HasFactory;

    /**
     * @param $theme
     * @return void
     */
    public function setThemeAttribute($theme)
    {
        if (!empty($theme)) {
            $this->attributes['theme'] = $this->filterData($theme);
        }
    }

    /**
     * @param $sender
     * @return void
     */
    public function setSenderAttribute($sender)
    {
        if (!empty($sender)) {
            $this->attributes['sender'] = $this->filterData($sender);
        }
    }

    /**
     * @param $data
     * @return void
     */
    public function setWebhookMethodAttribute($data)
    {
        if (!empty($data)) {
            $this->attributes['webhook_method'] = $this->filterData($data);
        }
    }

    /**
     * @param $data
     * @return void
     */
    public function setWebhookUrlAttribute($data)
    {
        if (!empty($data)) {
            $this->attributes['webhook_url'] = $this->filterData($data);
        }
    }

    /** Фильтрация входящих данных
     * @param string $data
     * @return string
     */
    private function filterData(string $data)
    {
        return htmlspecialchars(
            strip_tags(
                $data
            )
        );
    }
}
