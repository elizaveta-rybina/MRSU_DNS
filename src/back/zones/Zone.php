<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "zone".
 *
 * @property int $id
 * @property string $name
 * @property int $ttl
 * @property int $timeout
 * @property string $soa
 * @property string $admin
 * @property int $serial
 * @property int $refresh
 * @property int $retry
 * @property int $expire
 * @property int $minimum
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ZoneItem[] $zoneItems
 */
class Zone
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'soa', 'admin'], 'required'],
            [['ttl', 'timeout', 'serial', 'refresh', 'retry', 'expire', 'minimum', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['soa', 'admin'], 'string', 'max' => 64],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'ttl' => 'Ttl',
            'timeout' => 'Timeout',
            'soa' => 'SOA',
            'admin' => 'Admin',
            'serial' => 'Serial',
            'refresh' => 'Refresh',
            'retry' => 'Retry',
            'expire' => 'Expire',
            'minimum' => 'Minimum',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Gets query for [[ZoneItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZoneItems()
    {
        return $this->hasMany(ZoneItem::className(), ['zone_id' => 'id'])->orderBy('type');
    }

    public function addItem($item)
    {
        $item->zone_id = $this->id;
        $item->save();
    }

    public function clearItems()
    {
        ZoneItem::deleteAll(['zone_id' => $this->id]);
    }

    public function getSerialStr()
    {
        $num = strval($this->serial);
        if (strlen($num) < 2) $num = '0' . $num;
        return date('dmY') . $num;
    }

    public function checkZone()
    {
        $filename = '/tmp/' . $this->name . '.hosts';
        file_put_contents($filename, "\$TTL " . $this->ttl . "\r\n");
        file_put_contents($filename, $this->name . ".\t" . $this->timeout . "\tIN\tSOA\tns1.mrsu.ru.\t" . $this->admin . "\t(\r\n", FILE_APPEND);
        file_put_contents($filename, "\t\t\t" . $this->getSerialStr() . " ;serial\r\n", FILE_APPEND);
        file_put_contents($filename, "\t\t\t" . $this->refresh . " ;refresh\r\n", FILE_APPEND);
        file_put_contents($filename, "\t\t\t" . $this->retry . " ;retry\r\n", FILE_APPEND);
        file_put_contents($filename, "\t\t\t" . $this->expire . " ;expire\r\n", FILE_APPEND);
        file_put_contents($filename, "\t\t\t" . $this->minimum . " ;minimum\r\n)\r\n", FILE_APPEND);
        return false;
    }

    public function updateSerial()
    {
        date('dmY', $this->updated_at) == date('dmY') ? $this->serial++ : $this->serial = 1;
        if ($this->checkZone()) {
            $this->save();
        }
    }
}
