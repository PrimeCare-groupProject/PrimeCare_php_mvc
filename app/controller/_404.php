
<?php

class _404{
    use controller;
    public function index($a = '', $b = '', $c = ''){
        echo "404 page not found";
        $user = new User;
        // $result = $user->query('SELECT * FROM person');
        // $arr['id'] = 0;
        $arr['fname'] = 'user1';
        // $arr2['age'] = 22;
        // $result = $user->insert($arr);
        // $result = $user->where($arr);
        $result = $user->findAll();
        // $result = $user->findAccount();

        
        // show($result);
        // echo "this is the home controller";
        // $user->delete(0);
        $this->view('404');
    }
}

