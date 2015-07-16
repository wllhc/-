<?php

/**
 * PayAction.class.php
 *
 */
class PayAction extends userbaseAction
{
    public function _initialize()
    {
        parent::_initialize();
        //访问者控制
        if (!$this->visitor->is_login && in_array(ACTION_NAME, array('share_item', 'fetch_item', 'publish_item', 'like', 'unlike', 'delete', 'comment'))) {
            IS_AJAX && $this->ajaxReturn(0, L('login_please'));
            $this->redirect('user/login');
        }
    }

//所有的账务明细状态，消费。。。
    public function index()
    {
        $sub = $this->subList();
        $read = $this->readList();
        //$this->accountList();
        $leitai = $this->leitaiList();
        //总收入和总支出
        $allex = ($sub[0] + $read[0] + $leitai[0]);
        $allincome = ($sub[1] + $read[1] + $leitai[1]);
        $this->assign('allex', $allex);
        $this->assign('allincome', $allincome);
        $this->display();
    }

    public function subList()
    {
        $uid = session('uid');
//购买信息
        $subscribe = D('SubscribeView');
        import('ORG.Util.AjaxPage');
        $condition['uid'] = $uid;
        $condition['suid'] = $uid;
        $condition['_logic'] = 'OR';
        $count1 = $subscribe->where($condition)->count();
        $Page1 = new AjaxPage($count1, 10, 'SubAjax'); // 实例化分页类 传入总记录数和每页显示的记录数
//$limit_value = $Page1->firstRow . ',' . $Page1->listRows;
        $show1 = $Page1->show(); // 分页显示输出
        $this->assign('page1', $show1); // 赋值分页输出
        $subList = $subscribe->where($condition)
            ->limit($Page1->firstRow . ',' . $Page1->listRows)
            ->order('starttime desc')
            ->select();
        $this->assign('subscribe', $subList);
        $subscribe = M('Subscribe');
        $expense_sub = $subscribe->where('uid=' . $uid)
            ->sum('price');
        $profit_sub = $subscribe->where('suid=' . $uid)
            ->sum('exp_income');
        $this->assign('expense_sub', $expense_sub);
        $this->assign('profit_sub', $profit_sub);
        if ($this->isAjax()) {
            $this->display();
        }
        return array($expense_sub, $profit_sub);
    }


//提现
    public function atm()
    {
        if ($this->isAjax()) {
            $_payid = $this->_post('payid');
            if (empty($_payid)) {
                $this->error('提现的账户为空！请在个人资料中修改！');
            }

            $atmNum = $this->_post('num') * C('PROPORTION');
            $_num = $this->_post('num');
            if (100 > $_num) {
                $this->error('您申请的金额在大于100以上才能提现！');
            }

            $users = M('users');
            $user = $users->where('uid=' . session('uid'))->find();
            if ($atmNum > $user['money']) {
                $this->error('您申请的金额错误，不能大于你的余额');
            }

            $atm = M('atm');
            $allAtm = $atm->where('uid=' . session('uid') . ' AND atm_status != "end_pay"')
                ->Sum('num');

            //判断所有申请的总和
            if (($allAtm + $_num) * C('PROPORTION') > $user['money']) {
                $this->error('您正在申请提现的金额总和已经大于了您的余额！');
            }
            $a = $atm->create();
            $atm->uid = session('uid');
            $atm->createtime = time();
            $atm->atm_status = 'not_audit';
            if ($a) {
                if ($atm->add()) {
                    $this->success('申请提现成功！系统将在24小时内为您提现！');
                }
            }
        } else {
            $users = M('Users');
            $user = $users->where('uid=' . session('uid'))
                ->find();
            $this->assign('user', $user);
            $this->display();
        }
    }

//申请提现的明细
    public function atmList()
    {
        $atm = M('atm');
        $uid = session('uid');
        $atm_list = $atm->where('uid=' . $uid . ' and is_delete=0')
            ->select();
        $this->assign('atm', $atm_list);
        $this->display();
    }

    /**
     * ajax 删除未提现的申请
     *
     */
    public function delAtm()
    {
        if ($this->isAjax()) {
            $id = $this->_request('id');
            $atm = M('Atm');
            $aList = $atm->where('id=' . $id . ' AND (atm_status="not_audit" OR atm_status="audit") AND  uid=' . session('uid'))->find();
            if (empty($aList)) {
                $result = 2;
                $this->ajaxReturn($result, '', '');
            }
            if ($atm->where('id=' . $id)->data(array('is_delete' => 1))->save()) {
                $this->ajaxReturn(1, '', '');
            } else {
                $this->ajaxReturn(0, '', '');
            }
        }
    }



    /**
     * ajax 删除未付款的订单
     *
     */
    public function delRec()
    {
        if ($this->isAjax()) {
            $rid = htmlspecialchars($_REQUEST['rid']);
            $recharge = M('recharge');
            $rList = $recharge->where('rid=' . $rid . ' AND trade_status="WAIT_BUYER_PAY" AND  uid=' . session('uid'))
                ->find();

            if (empty($rList)) {
                $result = 2;
                $this->ajaxReturn($result, '', '');
            }

            $res = $recharge->where('rid=' . $rid)->delete();
            $result = $res ? 1 : 0;
            $this->ajaxReturn($result, '', '');
        }
    }
    public function cart2order()
    {
         if($this->isPost()){
            $item_id = $this->_post('item_id');
             $item = D('item');
             $map['id'] = array('in', $item_id);
             $price = $item->where($map)->sum('price');
             $this->dopayment($price);
         }
    }
    public function only2bay()
    {
        if ($this->isGet()) {
            $pid = $this->_get('id');
            $item = D('item');
            $info = $item->where('id=' . $pid)
                ->find();
            $this->dopayment( $info['price']);
        }
    }

    //购买处理
    private function dopayment($price)
    {
        C('TOKEN_ON', false);
        import('@.Payment.PaymentFactory');

        //订单号
        $out_trade_no = date('Ymdhis', time()) . mt_rand(10000, 99999);
        //订单标题
        $userinfo = session('user_info');
        $subject = '账户ID:' . $userinfo['username'] . '购买照片';
        //订单说明
        $body = '购买照片';
        //订单的充值数量
        $_total_fee = $price;
        $total_fee = floatval(htmlspecialchars($_total_fee));
        //商品地址，因为没商品，直接指向了网站首页
        $show_url = 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__;

        //订单参数，传给支付宝
        $params = array(
            'out_trade_no' => $out_trade_no,
            'subject' => $subject,
            'body' => $body,
            'total_fee' => $total_fee,
            'show_url' => $show_url
        );

        //充值记录数据
        $data = array(
            'uid' => $userinfo['id'],
            'username' => $userinfo['username'],
            'trade_sn' => $out_trade_no,
            'money' => $total_fee,
            'contactname' => '购买照片',
            'addtime' => time(),
            'status' => 'WAIT_BUYER_PAY',
        );

        //账目明细数据
        $data2 = array(
            'out_trade_no' => $out_trade_no,
            'trade_body' => $body,
            'uid' => $userinfo['id'],
            'num' => $total_fee,
            'trade_status' => 'WAIT_BUYER_PAY',
            'recharge_time' => time(),
        );
        M('recharge')->add($data2);
        M('account')->add($data);

        $pay_type = 'AlipayTwo';
        $payment = PaymentFactory::getPayment($pay_type);
        $html_text = $payment->buildForm($params);
        //此处关闭令牌，不然令牌将提交到支付宝中，将会验证不通过
        C('TOKEN_ON', false);
        $this->assign('form_html', $html_text);
        $this->assign('params', $params);
        $this->display('dopayment');

    }

    //购买后回调方法，非常重要，在线回调，即用户在没有关闭浏览器时
    public function pay_callback()
    {
        $this->_pay_callback_alipay_two();
    }

    //购买后回调方法，非常重要，离线回调，即用户关闭浏览器或者担保交易等等，支付宝会调用此方法
    public function pay_notify()
    {
        $this->_pay_notify_alipay_two();
    }

    private function _pay_callback_alipay_two()
    {
        import('@.Payment.PaymentFactory');
        $payment = PaymentFactory::getPayment('AlipayTwo');
        $result = $payment->verifyReturn();
        if ($result !== false) {
            $str = 'http://www.310zone.com/index.php/';
            $result['trade_status'] = trim($result['trade_status']);
            $record = M('recharge')->where("out_trade_no='$result[out_trade_no]'")->find();
            if ($record['trade_status'] == 'TRADE_FINISHED') {
                $this->success('充值成功！', $str . 'Pay/recharge?type=true');
                return;
            }
//等待发货
            if ($result['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
                if ($record && $record['num'] == $result['total_fee']) {
//自动发货
                    $response = $payment->send_goods(array('trade_no' => $result['trade_no']));
                    if ($response) {
                        M('recharge')->where("out_trade_no='$result[out_trade_no]'")
                            ->save(array('trade_status' => 'WAIT_BUYER_CONFIRM_GOODS', 'trade_no' => $result['trade_no']));
                        $this->success('请登录支付宝确认收货.<br />支付宝交易号：' . $result['trade_no'], $str . 'Pay/recharge?type=true');
                    } else {
                        M('recharge')->where("out_trade_no='$result[out_trade_no]'")
                            ->save(array('status' => 'WAIT_SELLER_SEND_GOODS', 'trade_no' => $result['trade_no']));
                        $this->error('发生错误，请联系客服.', $str . 'Pay/recharge?type=true');
                    }
                } else {
                    $this->error('支付失败<br />支付记录不存在或支付金额错误', $str . 'Pay/recharge?type=true');
                }
            } else if ($result['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {

//等待确认收货
                $this->success('请登录支付宝确认收货.<br />支付宝交易号：' . $result['trade_no'], $str . 'Pay/recharge?type=true');
            } else if ($result['trade_status'] == 'TRADE_FINISHED') {

                $data = array('trade_status' => 'TRADE_FINISHED',
                    'trade_no' => $result['trade_no'],
                );
                M('recharge')->where("out_trade_no='$result[out_trade_no]'")
                    ->save($data);
//充值成功，写入money
                $addMoney = $result['total_fee'] * C('PROPORTION');
                M('users')->where('uid=' . session('uid'))
                    ->setInc('money', $addMoney);


                $this->success('充值成功！', $str . 'Pay/recharge?type=true');
            } else {
                $this->error('支付失败<br />' . $result['trade_status'], $str . 'Pay/recharge?type=true');
            }
        } else {
            $this->error('支付失败', $str . 'Pay/recharge?type=true');
        }
    }

    private function _pay_notify_alipay_two()
    {
        import('@.Payment.PaymentFactory');
        $payment = PaymentFactory::getPayment('AlipayTwo');
        $result = $payment->verifyNotify();
        if ($result !== false) {

            $result['trade_status'] = trim($result['trade_status']);
            $record = M('recharge')->where("out_trade_no='$result[out_trade_no]'")->find();
            if ($record['trade_status'] == 'TRADE_FINISHED') {
                exit('success');
            }
//等待发货
            if ($result['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {

                if ($record && $record['num'] == $result['total_fee']) {
//自动发货
                    $response = $payment->send_goods(array('trade_no' => $result['trade_no']));
                    if ($response) {
                        M('recharge')->where("out_trade_no='$result[out_trade_no]'")
                            ->save(array('trade_status' => 'WAIT_BUYER_CONFIRM_GOODS', 'trade_no' => $result['trade_no']));
                    } else {
                        M('recharge')->where("out_trade_no='$result[out_trade_no]'")
                            ->save(array('status' => 'WAIT_SELLER_SEND_GOODS', 'trade_no' => $result['trade_no']));
                    }
                } else {
                    exit('fail');
                }
            } else if ($result['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
//等待确认收货
                M('recharge')->where("out_trade_no='$result[out_trade_no]'")
                    ->save(array('status' => 'WAIT_BUYER_CONFIRM_GOODS', 'trade_no' => $result['trade_no']));
            } else if ($result['trade_status'] == 'TRADE_FINISHED') {
                M('recharge')->where("out_trade_no='$result[out_trade_no]'")
                    ->save(array('trade_status' => 'TRADE_FINISHED', 'trade_no' => $result['trade_no']));
                $record = M('recharge')->where("out_trade_no='$result[out_trade_no]'")->find();
                $addMoney = $result['total_fee'] * C('PROPORTION');
                M('users')->where('uid=' . $record['uid'])->setInc('money', $addMoney);
                exit('success');
            } else {
                exit('fail');
            }
        } else {
            exit('fail');
        }
    }

}