<?php

namespace samkoch\activitylog;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "activity_log".
 *
 * The followings are the available columns in table 'activity_log':
 * @property integer $id
 * @property string $category
 * @property integer $tstamp
 * @property string $ip
 * @property string $session_id
 * @property integer $user_id
 * @property string $message
 */
class ActivityLog extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return ActivityLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'activity_log';
    }

    public static function log($category, $message = '', $modelName = '', $recordId = '', $params = '')
    {
        $log = new self();

        $log->tstamp = time();

        if (PHP_SAPI !== 'cli') {
            $log->ip = $_SERVER['REMOTE_ADDR'];
            $log->session_id = Yii::$app->session->id;

            if (!Yii::$app->user->isGuest) {
                $log->user_id = Yii::$app->user->id;
            }
        }

        $log->category = $category;
        $log->message = $message;
        $log->params = is_array($params) ? print_r($params, true) : $params;
        $log->model = $modelName;
        $log->record_id = $recordId;

        return $log->save(false);
    }

}