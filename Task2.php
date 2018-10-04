<?php
/**
 * Created by PhpStorm.
 * User: alexandr
 * Date: 04.10.18
 * Time: 23:25
 */

//1 способ (Кэширование данных-Результата)
function ($date, $type)
{
    $userId = Yii::$app->user->id;
    $cacheKey = ['some-data-model-items', 'date' => $date, 'type' => $type, 'user_id' => $userId];
    if (!$result = Yii::$app->cache->get($cacheKey)) {
        $dataList = SomeDataModel::find()->where(['date' => $date, 'type' => $type, 'user_id' => $userId])->all();
        $result = [];
        if (!empty($dataList)) {
            foreach ($dataList as $dataItem) {
                $result[$dataItem->id] = ['a' => $dataItem->a, 'b' => $dataItem->b];
            }
        }
        Yii::$app->cache->set($cacheKey, $result);
    }

    return $result;
}

//2 способ (Кэширование только запроса)
function ($date, $type)
{
    $userId = Yii::$app->user->id;

    $dataList = SomeDataModel::getDb()->cache(function () {
        return SomeDataModel::find()->where(['date' => $date, 'type' => $type, 'user_id' => $userId])->all();
    });

    $result = [];
    if (!empty($dataList)) {
        foreach ($dataList as $dataItem) {
            $result[$dataItem->id] = ['a' => $dataItem->a, 'b' => $dataItem->b];
        }
    }
    return $result;
}