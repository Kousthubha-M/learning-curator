<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ActivitiesUsers Controller
 *
 * @property \App\Model\Table\ActivitiesUsersTable $ActivitiesUsers
 *
 * @method \App\Model\Entity\ActivitiesUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ActivitiesUsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Activities', 'Users'],
        ];
        $activitiesUsers = $this->paginate($this->ActivitiesUsers);

        $this->set(compact('activitiesUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Activities User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $activitiesUser = $this->ActivitiesUsers->get($id, [
            'contain' => ['Activities', 'Users'],
        ]);

        $this->set('activitiesUser', $activitiesUser);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $activitiesUser = $this->ActivitiesUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $activitiesUser = $this->ActivitiesUsers->patchEntity($activitiesUser, $this->request->getData());
            if ($this->ActivitiesUsers->save($activitiesUser)) {
                //print(__('The activities user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            //print(__('The activities user could not be saved. Please, try again.'));
        }
        $activities = $this->ActivitiesUsers->Activities->find('list', ['limit' => 200]);
        $users = $this->ActivitiesUsers->Users->find('list', ['limit' => 200]);
        $this->set(compact('activitiesUser', 'activities', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Activities User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $activitiesUser = $this->ActivitiesUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $activitiesUser = $this->ActivitiesUsers->patchEntity($activitiesUser, $this->request->getData());
            if ($this->ActivitiesUsers->save($activitiesUser)) {
                //print(__('The activities user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            //print(__('The activities user could not be saved. Please, try again.'));
        }
        $activities = $this->ActivitiesUsers->Activities->find('list', ['limit' => 200]);
        $users = $this->ActivitiesUsers->Users->find('list', ['limit' => 200]);
        $this->set(compact('activitiesUser', 'activities', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Activities User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        // #TODO write a proper policy for this, don't skip it
        $this->Authorization->skipAuthorization();
        $activitiesUser = $this->ActivitiesUsers->get($id);
       //$user = $this->request->getAttribute('authentication')->getIdentity();
       // $activitiesUser = $this->ActivitiesUsers->find('all')->where(['user_id'=>$user->id])->where(['activity_id'=>$aid]);
        //$activitiesUser = $this->ActivitiesUsers->get($user->id,$aid);
         //$foo = $activitiesUser->toArray();
         //echo '<pre>';print_r($foo); exit;
        // foreach($activitiesUser as $u) { echo $u; }
        // exit;
        if ($this->ActivitiesUsers->delete($activitiesUser)) {
            //print(__('The activities user has been deleted.'));

        } else {
            //print(__('The activities user could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }
    /**
     * User action claim method. Users need to be able to identify which
     * actions that they've taken or otherwise consumed so that we can
     * tell them how far along their journey they are. 
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function claim()
    {
        // #TODO write a proper policy for this, don't skip it
        $this->Authorization->skipAuthorization();
        $now = date('Y-m-d H:i:s');
        $user = $this->request->getAttribute('authentication')->getIdentity();
        $activitiesUsers = $this->ActivitiesUsers->newEmptyEntity();
        $activitiesUsers->user_id = $user->id;
        $activitiesUsers->activity_id = $this->request->getData()['activity_id'];
        $activitiesUsers->started = $now;

        if ($this->request->is('post')) {
            if ($this->ActivitiesUsers->save($activitiesUsers)) {
                //print(__('You have claimed that activity!'));
                return $this->redirect($this->referer());
            }
            print(__('The users action could not be saved. Please, try again.'));
        }
    }
}
