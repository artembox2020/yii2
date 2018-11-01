<?php

namespace frontend\services\globals;

use common\models\User;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use \yii\jui\AutoComplete;
use yii\web\JsExpression;

/**
 * Class EntityHelper
 * @package frontend\services\globals
 */
class EntityHelper implements EntityHelperInterface
{
    /**
     * @param Instance $instance
     * @param int $status
     * @return array
     */
    public function tryFilteredStatusDataEx($instance, $status)
    {
        $entity = new Entity();
        $units = $instance::find()
            ->andWhere(['company_id' => $entity->getCompanyId(), 'status' => $status])
            ->all();
        if (!$units) {
            $units = [];
        }

        return $units;   
    }
    
    /**
     * @param Instance $instance
     * @param int $status
     * @param array $params
     * @return array
     * @throws \yii\web\HttpException
     */
    public function tryFilteredStatusDataMapped($instance, $status, Array $params, Array $unitIds = [])
    {
        $units = $this->tryFilteredStatusDataEx($instance, $status);

        // addition of some more units if specified
        if (!empty($unitIds)) {
            $entity = new Entity();
            $ids = ArrayHelper::getColumn($units, 'id');
            $unitIds = array_diff($unitIds, $ids);
            $additionalUnits = $entity->tryUnitsPertainCompanyByIds($unitIds, $instance);
            if ($additionalUnits) {
                $units = array_merge($units, $additionalUnits);
            }
        }

        $maps = [];
        $array_keys = array_keys($params);
        $key = !empty($array_keys[0]) ? $array_keys[0] : false;
        if (!$key) {

            return $maps;
        }
        foreach ($units as $unit) {
            $value = $this->tryUnitRelationData($unit, $params);
            $id = $this->tryUnitRelation($unit, $key);
            if ($id && $value) {
                $maps[] = (object)['id' => $id, 'value' => $value];
            }
        }

        return $maps;
    }

    /**
     * @param array $params
     * @return \yii\jui\AutoComplete
     * @throws \yii\web\HttpException
     */
    public function AutoCompleteWidgetFilteredData(Array $params)
    {
        extract($params);
        $queryString = '';

        // exclude params ['id', 'foreignId', '_pjax'] from query string
        if (!empty(Yii::$app->request->queryParams)) {
            $excludeParams = ['id' => 1, 'foreignId' => 1, '_pjax' => 1];
            $queryParams = array_diff_key(Yii::$app->request->queryParams, $excludeParams);
            $queryString = '&'.http_build_query($queryParams);
        }

        $selectExpr = new JsExpression(
            "function( event, ui )
            {
                if (typeof ui.item != 'undefined' && ui.item != null)
                {
                    location.href = 
                        '{$url}'+'?id='+{$model->id}+
                        '&foreignId='+ui.item.id+'{$queryString}';
                }
			}"
		);

        return AutoComplete::widget([
            'name' => $name,

            'options' => $options,

            'clientOptions' => [
                'source' => $source,
                'autoFill' => true,
                'select' => $selectExpr,
            ],
        ]);    
    }

    /**
     * @param Instance $unit
     * @param string $relation
     * @return Instance|bool
     */
    public function tryUnitRelation($unit, $relation)
    {
        if (!$unit) {
            
            return false;
        }

        if (empty($relation) || empty($unit->$relation)) {

            return false;
        } else {

            return $unit->$relation;
        }
    }

    /**
     * @param Instance $unit
     * @param array $params
     * @return string|bool
     * @throws \yii\web\HttpException
     */
    public function tryUnitRelationData($unit, $params)
    {
        try {
            $relation = array_keys($params)[0];
            $names = $params[$relation];
            $glue = empty($params[0]) ? ' ' : $params[0];

            if (!$relationObject = $this->tryUnitRelation($unit, $relation)) {

                return false;
            }

            if (!is_object($relationObject)) {
                $relationObject = $unit;
            }

            if (is_array($names)) {
                $value = '';
                foreach($names as $name) {
                    $value.= $relationObject->$name.$glue;
                }
                if (!empty($value)) {
                    $value = mb_substr($value, 0, -mb_strlen($glue));
                }

            } else {
                $value = $relationObject->$names;
            }
        }
        catch (Exception $e) {
            throw new \yii\web\HttpException(
                500, 
                Yii::t('common', 'Invalid Array [Key => Value] Configuration')
            );
        }

        return $value;    
    }
    
    /**
     * Submits the form, defined, on selectors events
     * 
     * @param string $formSelector
     * @param array $eventSelectors
     * @param int $timeDelay
     * @return javascript
     */
    public function submitFormOnInputEvents($formSelector, Array $eventSelectors, $timeDelay = 1800)
    {
        $setFocusIfNecessary = new JsExpression(
            "function setFocusIfNecessary(form)
             {
                 var selectionName = form.querySelector('input[name=selectionName]').value;
                 var selectionCaretPos = form.querySelector('input[name=selectionCaretPos]').value;
                 if (selectionName.length > 0 && selectionCaretPos.length > 0) {
                     var input = form.querySelector('input[type=text][name=' + selectionName +']');
                     if (typeof input != 'undefined' && input !== null) {
                         input.focus();
                         input.selectionStart = input.value.length;
                         var event = document.createEvent('HTMLEvents');
                         event.initEvent('change', false, true);
                         input.dispatchEvent(event);
                     }
                 }
             }"
        );
        $jsCode = $setFocusIfNecessary;
        
        foreach ($eventSelectors as $event => $selector) {
            $jsExpr = new JsExpression(
                "(function()
                 {
                     var form = document.querySelector('{$formSelector}');
                     var formElements = form.querySelectorAll('{$selector}');
                     setFocusIfNecessary(form);
                     for ( var i = 0; i < formElements.length; ++i)
                     {    
                          var formElement = formElements[i];     
                          formElement.on{$event} = function() 
                          {
                              if ('{$event}' == 'keyup') {
                                  clearInterval(hKeyUpInterval);
                                  var form = document.querySelector('{$formSelector}');
                                  var thisObj = this;
                                  hKeyUpInterval = setInterval(function()
                                  {
                                      var uiWidgetContents = document.querySelectorAll('.ui-widget-content.ui-autocomplete');
                                      var signAllHidden = true;
                                      for (var i = 0; i < uiWidgetContents.length; ++i) {
                                           var styleObject = window.getComputedStyle(uiWidgetContents[i]);
                                           if (styleObject.getPropertyValue('display') != 'none') {
                                               signAllHidden = false;
                                               break;
                                           }
                                      }
                                      
                                      if (signAllHidden) {
                                          fillHiddenSelectionFields(form, thisObj);
                                          submitForm('{$formSelector}');
                                          clearInterval(hKeyUpInterval);
                                      }
                                  }, {$timeDelay});
                              } else {
                                  submitForm('{$formSelector}');
                              }
                          };
                     }
                 })();"
            );
            $jsCode .= ' '.$jsExpr;
        }

        return '<script>'.$jsCode.'</script>';
    }
    
    /**
     * Removes redundant grids on the page, generated by Pjax
     * 
     * @param string $gridSelector
     * @return javascript
     */
    public function removeRedundantGrids($gridSelector)
    {
        $jsCode = new JsExpression(
            "var redundantGrids = document.querySelectorAll('{$gridSelector}');
             for (var i = 1; i < redundantGrids.length; ++i)
             {
                redundantGrids[i].parentNode.removeChild(redundantGrids[i]);
             }
            "
        );

        return '<script>'.$jsCode.'</script>';
    }
    
    /**
     * Creates params based on $_GET data
     * 
     * @param array $requiredParams
     * @return array
     */
    public function makeParamsFromRequest(Array $requiredParams)
    {
        $params = Yii::$app->request->get();
        foreach ($requiredParams as $key => $param) {
            if (is_int($key)) {
                if (!isset($params[$param])) {
                    $params[$param] = null;
                }
            } else {
                foreach ($param as $p) {
                    if (!isset($params[$key])){
                        $params[$key] = null;
                    } else {
                        if (!is_array($p)) {
                            if (!isset($params[$key][$p])) {
                                $params[$key][$p] = null;
                            }
                        }
                    }
                }
            }
        }

        return $params;
    }

    /**
     * Renders popup window view
     * 
     * @param string $imgSrcs
     * @param string $text
     * @param string $labelStyle
     * @param string $blockStyle
     * @return string
     */
    public static function makePopupWindow($imgSrcs, $text,  $labelStyle = '', $blockStyle = '')
    {

        return Yii::$app->view->render(
            '/monitoring/data/popupWindow.php',
            ['imgSrcs' => $imgSrcs, 'text' => $text, 'labelStyle' => $labelStyle, 'blockStyle' => $blockStyle]
        );
    }

    /**
     * Gets the base query from -data table (history)
     * 
     * @param timestamp $start
     * @param timestamp $end
     * @param Instance $instance
     * @param Instance $bInstance
     * @param string $field
     * @param string $select
     * @return ActiveDbQuery
     */
    public function getBaseUnitQueryByTimestamps($start, $end, $instance, $bInstance, $field, $select)
    {
        $baseQuery = $instance::find()->select($select)
                                      ->andWhere([$field => $bInstance->id])
                                      ->andWhere(['>=', 'created_at', $start])
                                      ->andWhere(['<', 'created_at', $end]);

        return $baseQuery;
    }

    /**
     * Makes array of non-zero intervals from -data table (history)
     * 
     * @param timestamp $start
     * @param timestamp $end
     * @param Instance $instance
     * @param Instance $bInstance
     * @param string $fieldInstance
     * @param string $select
     * @param string $field
     * @return array
     */
    public function makeNonZeroIntervalsByTimestamps($start, $end, $instance, $bInstance, $fieldInstance, $select, $field)
    {
        $intervals = [];
        $baseQuery = $this->getBaseUnitQueryByTimestamps($start, $end, $instance, $bInstance, $fieldInstance, $select);
        $queryS1 = clone $baseQuery;
        $queryS1 = $queryS1->andWhere([$field => 0])
                           ->orderBy(['created_at' => SORT_ASC])
                           ->limit(1);

        if ($queryS1->count() > 0) {
            $item = $queryS1->one();
            if ($item->created_at > $start) {
                $intervals[] = [
                    'start' => $start,
                    'end' => $item->created_at
                ];

                return array_merge(
                    $intervals,
                    $this->makeNonZeroIntervalsByTimestamps($item->created_at, $end, $instance, $bInstance, $fieldInstance, $select, $field)
                );
            } else {
                $queryS2 = clone $baseQuery;
                $queryS2 = $queryS2->andWhere(['!=', $field, 0])
                                   ->andWhere(['>=', 'created_at', $item->created_at])
                                   ->andWhere(['<', 'created_at', $end])
                                   ->orderBy(['created_at' => SORT_ASC])
                                   ->limit(1);

                if ($queryS2->count() > 0) {
                    $item = $queryS2->one();
                    $queryS3 = clone $baseQuery;
                    $queryS3 = $queryS3->andWhere(['<', 'created_at', $item->created_at])
                                       ->orderBy(['created_at' => SORT_DESC])
                                       ->limit(1);
                    $item = $queryS3->one();
                    $newStart = $item->created_at;

                    $queryS4 = clone $baseQuery;
                    $queryS4 = $queryS4->andWhere(['=', $field, 0])
                                       ->andWhere(['<', 'created_at', $end])
                                       ->andWhere(['>', 'created_at', $newStart])
                                       ->orderBy(['created_at' => SORT_ASC])
                                       ->limit(1);
                    $item = $queryS4->one();

                    if (!$item) {

                        return [
                            0 => ['start' => $newStart, 'end' => $end]
                        ];
                    } else {
                        $intervals[] = ['start' => $newStart, 'end' => $item->created_at];
                        $intervals = array_merge(
                            $intervals,
                            $this->makeNonZeroIntervalsByTimestamps($item->created_at, $end, $instance, $bInstance, $fieldInstance, $select, $field)
                        );

                        return $intervals;
                    }
                }
            }
        } else {

            return [
                0 => ['start' => $start, 'end' => $end]
            ];
        }

        return $intervals;
    }

    /**
     * Gets unit income by the ready non-zero time interval from -data table (history)
     * 
     * @param timestamp $start
     * @param timestamp $end
     * @param Instance $inst
     * @param Instance $bInst
     * @param string $fieldInst
     * @param string $select
     * @param string $field
     * @param boolean $isFirst
     * @return decimal
     */
    public function getUnitIncomeByNonZeroTimestamps($start, $end, $inst, $bInst, $fieldInst, $select, $field, $isFirst)
    {
        $baseQuery = $this->getBaseUnitQueryByTimestamps($start, $end, $inst, $bInst, $fieldInst, $select);
        $queryS1 = clone $baseQuery;
        $queryS1 = $queryS1->orderBy(['created_at' => SORT_ASC])->limit(1);

        if ($queryS1->count() == 0) {

            return 0;
        }

        $queryS2 = clone $baseQuery;
        $queryS2 = $queryS2->orderBy(['created_at' => SORT_DESC])->limit(1);

        $itemStart =  $queryS1->one();
        $itemEnd = $queryS2->one();

        if ($isFirst && $itemStart->$field != 0) {
            $queryS3 = clone $baseQuery;
            $queryS3 = $queryS3->where(['<', 'created_at', $start])
                               ->andWhere(['>=', 'created_at', $bInst->created_at])
                               ->andWhere(['>=', 'created_at', ($start - 3600*3)])
                               ->andWhere(['is_deleted' => false])
                               ->andWhere([$fieldInst => $bInst->id])
                               ->orderBy(['created_at' => SORT_DESC])
                               ->limit(1);

            if ($queryS3->count() > 0) {
                $item = $queryS3->one();
                if ($item->$field < $itemStart->$field) {
                    $itemStart = $item;
                }
            }

        }

        return ($itemEnd->$field - $itemStart->$field);
    }

    /**
     * Calculates unit idle hours bytimestamps
     * 
     * @param timestamp $start
     * @param timestamp $end
     * @param Instance $inst
     * @param Instance $bInst
     * @param string $fieldInst
     * @param string $select
     * @param int $timeIdleHours
     * @return decimal
     */
    public function getUnitIdleHoursByTimestamps($start, $end, $inst, $bInst, $fieldInst, $select, $timeIdleHours)
    {
        $stepInterval = $timeIdleHours * 3600;
        $idleHours = 0.00;
        $endTimestamp = $start + $stepInterval;
        for ($timestamp = $start; $endTimestamp <= $end; $timestamp += $stepInterval, $endTimestamp = $timestamp + $stepInterval) {
            $query = $this->getBaseUnitQueryByTimestamps($timestamp, $endTimestamp, $inst, $bInst, $fieldInst, $select);
            if ($query->count() > 0) {
                $item = $query->orderBy(['created_at' => SORT_DESC])->limit(1)->one();
                $timestamp = $item->created_at - $stepInterval + 1;
                continue;
            } else {
                $query = $this->getBaseUnitQueryByTimestamps($endTimestamp, $end, $inst, $bInst, $fieldInst, $select);
                if ($query->count() == 0) {
                    $idleHours += ((float)$end - $timestamp) / 3600;
                    break;
                } else {
                    $item = $query->orderBy(['created_at' => SORT_ASC])->limit(1)->one();
                    $timeDiff = $item->created_at - $endTimestamp;
                    $idleHours += $timeIdleHours + ((float)$timeDiff / 3600);
                    $timestamp = $item->created_at - $stepInterval + 1;
                    continue;
                }
            }
        }

        return $idleHours;
    }
}
