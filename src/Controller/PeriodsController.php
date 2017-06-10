<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Request;
use DateTime;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PeriodsController extends AppController {

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     * 
     */
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['index', 'add', 'data']);
        $this->PeriodsTable = TableRegistry::get('periods');
    }

    /**
     * Display index page.
     */
    public function index() {
        $period = $this->PeriodsTable->newEntity();
        $this->set('period', $period);
        $this->set('title', 'Add Period');

        $this->viewBuilder()->layout('dashboard');
    }

    /**
     * Save time period.
     */
    public function add() {
        if ($this->request->is('ajax')) {
            $ePeriod = $this->PeriodsTable->newEntity();
            $Period = $this->PeriodsTable->patchEntity($ePeriod, $this->request->data);
            if (empty($Period->errors())) {
                $this->PeriodsTable->save($Period);
                $status = '200';
                $message = '';
            } else {
                $error_msg = [];
                foreach ($Period->errors() as $errors) {
                    if (is_array($errors)) {
                        foreach ($errors as $error) {
                            $error_msg[] = $error;
                        }
                    } else {
                        $error_msg[] = $errors;
                    }
                }
                $status = 'error';
                $message = $error_msg;
            }
            $this->set("status", $status);
            $this->set("message", $message);
            $this->set('_serialize', ['status', 'message']);
            $this->viewBuilder()->layout(false);
        }
    }

    /**
     * Calculates the total hours per day, considering period overlaps.
     * @param  array   $periods
     * @return integer
     */
    public function total_hours($periods) {
        ksort($periods);
        do {
            $count = count($periods);
            foreach ($periods as $key1 => $period1) {
                foreach ($periods as $key2 => $period2) {
                    if ($key2 > $key1 and $period1[0] <= $period2[1] and $period1[1] >= $period2[0]) {
                        $periods[] = [min($period1[0], $period2[0]), max($period2[1], $period1[1])];
                        unset($periods[$key1], $periods[$key2]);
                    }
                }
            }
        } while ($count > count($periods));
        return array_reduce($periods, function ($total, $period) {
            return $total + $period[0]->diff($period[1])->h;
        });
    }

    /**
     * Display chart with data.
     * @return json
     */
    public function data() {
        if ($this->request->is('ajax')) {
            $periods = $this->PeriodsTable->find('all');
            foreach ($periods as $period) {
                $start = substr_replace($period->created, ' ' . $period->start_time, -8);
                $end = substr_replace($period->created, ' ' . $period->end_time, -8);
                $startTime = strtotime($start) * 1000;
                $EndTime = strtotime($end) * 1000;
                $p[] = [new DateTime($period->start_time), new DateTime($period->end_time)];
                $data[] = json_decode('[' . $startTime . ',' . $EndTime . ']', true);
            }

            $totalHrs = $this->total_hours($p);

            $this->set('data', $data);
            $this->set('hrs', $totalHrs);
            $this->set('_serialize', ['data', 'hrs']);
            $this->viewBuilder()->layout(false);
        }
    }

}
