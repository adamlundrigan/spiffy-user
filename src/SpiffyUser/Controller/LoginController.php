<?php

namespace SpiffyUser\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class LoginController extends AbstractActionController
{
    /**
     * @return Response
     */
    public function logoutAction()
    {
        if ($this->identity()) {
            $this->getAuthExtension()->logout();
        }
        return $this->redirect()->toRoute('spiffy_user/login');
    }

    /**
     * @return array|Response
     */
    public function loginAction()
    {
        $ext = $this->getAuthExtension();

        if ($this->identity()) {
            return $this->redirect()->toRoute('spiffy_user');
        }
        $prg  = $this->prg();
        $form = $this->getAuthExtension()->getLoginForm();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            if ($ext->login($prg)->isValid()) {
                return $this->redirect()->toRoute('spiffy_user');
            }
        }

        return array('form' => $form);
    }

    /**
     * @return \SpiffyUser\Extension\Authentication
     */
    public function getAuthExtension()
    {
        return $this->plugin('zfcUserExtension')->get('authentication');
    }
}
