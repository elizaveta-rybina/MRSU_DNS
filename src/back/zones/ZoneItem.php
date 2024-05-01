<?php

namespace app\models;

/**
 * This is the model class for table "zone_item".
 *
 * @property int $id
 * @property int $zone_id
 * @property string $name
 * @property int $priority
 * @property int $ttl
 * @property string $type
 * @property string $content
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Zone
 */

require_once 'Zone.php';

class ZoneItem
{
    public static function tableName()
    {
        return 'zone_item';
    }

    public function rules()
    {
        return [
            [['zone_id', 'name', 'type', 'content'], 'required'],
            [['zone_id', 'priority', 'ttl', 'created_at', 'updated_at'], 'integer'],
            [['name', 'content'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 10],
            [['zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zone::className(), 'targetAttribute' => ['zone_id' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zone_id' => 'Zone ID',
            'name' => 'Запись',
            'priority' => 'Приоритет',
            'ttl' => 'TTL',
            'type' => 'Тип',
            'content' => 'IP адрес или значение',
            'created_at' => 'Created At',
            'updated_at' => 'Обновлено',
        ];
    }

    //одна строка
    public static function loadFromString($line)
    {
        $item = new ZoneItem();
        $str = str_replace("\t", " ", $line);
        $str = str_replace("  ", " ", $str);
        $parts = explode(" ", $str);
        $item->name = $parts[0];
        $index = 1;
        if (is_numeric($parts[$index])) {
            $item->ttl = $parts[$index];
            $index++;
        }
        if ($parts[$index] == "IN") $index++;
        $item->type = $parts[$index];
        switch ($item->type) {
            case "NS":
            case "A":
            case "CNAME":
                $item->content = $parts[$index + 1];
                break;
            case "MX":
                $item->priority = $parts[++$index];
                $item->content = $parts[++$index];
                while ($index < count($parts) - 1) {
                    $item->content .= " " . $parts[$index++];
                }
                break;
            case "TXT":
            case "SRV":
                $item->content = $parts[++$index];
                while ($index < count($parts)) {
                    $item->content .= " " . $parts[$index++];
                }
                break;
        }
        return $item;
    }

    public function zoneItem()
    {
        if ($this->validate()) {
            $this->save(false);
            return true;
        }

        return false;
    }

    public function getTypeItems()
    {
        $types = ZoneItemType::find()->orderBy('id')->all();
        return ArrayHelper::map($types, 'id','id');
    }

    public function assign($model){
        $this->name = $model->name;
        $this->priority = $model->priority;
        $this->ttl = $model->ttl;
        $this->type = $model->type;
        $this->content = $model->content;
    }
}
