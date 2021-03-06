<?php

class ElectionController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl',
            array(
                'BanFilter + index, nominate',
            ),
            array(
                'VerifyFilter + index, nominate',
            ),
            array(
                'IPLogFilter'
            ),
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index','results','nominate'),
                'users' => array('@'),
            ),
            array('allow',
                'actions'=>array('results'),
                'users' => array('*'),
            ),
            array('deny',
                'actions' => array('index','nominate'),
                'users' => array('*'),
            )
        );
    }

    public function actionIndex()
    {
        $elections = Election::model()->findAll('startTime <= NOW() AND endTime > NOW()');
        $user = User::model()->findByPk(Yii::app()->user->getId());

        foreach($elections as $k => $election)
        {
            $time = $election->nominateStartTime->getTimestamp();
            $joinTime = strtotime($user->joinDate);
            if($joinTime > $time) unset($elections[$k]);
        }

        if(!count($elections))
        {
            $this->render('../site/error',array('message' => 'In order to prevent election fraud, you are unable to participate in elections where nominations began before you joined the Watch'));
            return;
        }

        $model = new ElectionForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // if its an actual submission
        if(isset($_POST['ElectionForm']))
        {
            if($user->deserter == User::DESERTER_ADMIN)
                $this->jsonOut(array('success' => false));

            $model->attributes=$_POST['ElectionForm'];
            if($model->validate())
            {
                // First, set the vote.
                ElectionVote::model()->deleteAllByAttributes(array(
                    'electionID' => $model->electionID,
                    'voterID' => Yii::app()->user->getId(),
                ));

                $success = true;
                foreach($model->getCandidateArray() as $candidateID)
                {
                    $vote = new ElectionVote;
                    $vote->electionID = $model->electionID;
                    $vote->candidateID = $candidateID;
                    $vote->voterID = Yii::app()->user->getId();
                    $success = $vote->save() && $success;
                }

                if(isset($_POST['ajax']))
                {
                    $this->jsonOut(array('success' => $success));
                }
            }
        }

        $this->setPageTitle('Current Elections');
        //$elections = Election::model()->findAll('startTime <= NOW() AND endTime > NOW()');
        $this->render('index',array('elections' => $elections));
    }

    public function actionNominate()
    {
        throw new CHttpException(501,"Not Yet Implemented");
    }

    public function actionResults()
    {
        $elections = Election::model()->findAll(array('condition' => 'endTime <= NOW()', 'order' => 'endTime DESC'));

        $this->setPageTitle('Election Results');
        $this->render('result',array('elections' => $elections));
    }
}