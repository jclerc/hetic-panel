<?php

class AdminController extends Controller {

    const DEPENDENCIES = ['Flash'];

    const PERMISSION_REQUIRED = User::STAFF;

    public function index() {}

    public function promotion() {

        if (POST and isset($_POST['method'])) {

            if ($_POST['method'] === 'add' or $_POST['method'] === 'update') {

                $year = intval($_POST['year']);

                try {

                    if ($_POST['method'] === 'add') {

                        $this->set('method-add', true);
                        $promotion = Promotion::make();
                        $promotion->create(
                            $year
                        );
                        $this->flash->set(true, 'Promotion ajoutée !');
                        $this->set('method-add', false);

                    } else if ($_POST['method'] === 'update') {

                        $promotion = Promotion::make($_POST['edit']);
                        if ($promotion->exists()) {
                    
                            $promotion->validate(
                                $year
                            );

                            $promotion->set([
                                'year' => $year,
                            ]);
                            
                            $promotion->save();
                            $this->flash->set(true, 'Promotion modifiée !');

                        }
                    }

                } catch (Exception $e) {
                    $this->flash->set(false, $e->getMessage());
                }

            } else if ($_POST['method'] === 'retrieve') {

                $promotion = Promotion::make($_POST['edit']);
                if ($promotion->exists()) {
                    $this->set('values', [
                        'promotion' => $promotion,
                    ]);
                } else {
                    $this->flash->set(false, 'Aucune promotion sélectionnée.');
                }

            } else if ($_POST['method'] === 'delete') {

                $promotion = Promotion::make($_POST['delete']);
                if ($promotion->exists()) {
                    $promotion->delete();
                    $this->flash->set(true, 'P' . $promotion->getYear() . ' supprimé !');
                } else {
                    $this->flash->set(false, 'Aucune promotion sélectionnée.');
                }

            }

        }

        $promotions = Promotion::sort(Promotion::make()->find());
        $this->set('promotions', $promotions);

    }

    public function group() {

        if (POST and isset($_POST['method'])) {

            if ($_POST['method'] === 'add' or $_POST['method'] === 'update') {

                $index     = intval($_POST['index']);
                $promotion = Promotion::make($_POST['promotion']);

                try {

                    if ($_POST['method'] === 'add') {

                        $this->set('method-add', true);
                        $group = Group::make();
                        $group->create(
                            $index,
                            $promotion
                        );
                        $this->flash->set(true, 'Groupe ajouté !');
                        $this->set('method-add', false);

                    } else if ($_POST['method'] === 'update') {

                        $group = Group::make($_POST['edit']);
                        if ($group->exists()) {
                    
                            $group->validate(
                                $index,
                                $promotion
                            );

                            $group->set([
                                'index'     => $index,
                                'promotion' => $promotion,
                            ]);
                            
                            $group->save();
                            $this->flash->set(true, 'Groupe modifié !');

                        }
                    }

                } catch (Exception $e) {
                    $this->flash->set(false, $e->getMessage());
                }

            } else if ($_POST['method'] === 'retrieve') {

                $group = Group::make($_POST['edit']);
                if ($group->exists()) {
                    $this->set('values', [
                        'group'     => $group,
                        'index'     => $group->getIndex(),
                        'promotion' => $group->getPromotion()->getId(),
                    ]);
                } else {
                    $this->flash->set(false, 'Aucun groupe sélectionné.');
                }

            } else if ($_POST['method'] === 'delete') {

                $group = Group::make($_POST['delete']);
                if ($group->exists()) {
                    $group->delete();
                    $this->flash->set(true, 'G' . $group->getIndex() . ' supprimé !');
                } else {
                    $this->flash->set(false, 'Aucun groupe sélectionné.');
                }

            }

        }

        $groupList = Group::sort(Group::make()->find());
        $groups = [];

        foreach ($groupList as $group) {
            $groups[ 'P' . $group->getPromotion()->getYear() ][ $group->getIndex() ] = $group;
        }

        $this->set('groups', $groups);

        $promotions = Promotion::sort(Promotion::make()->find());
        $this->set('promotions', $promotions);

    }

    public function course() {

        if (POST and isset($_POST['method'])) {

            if ($_POST['method'] === 'add' or $_POST['method'] === 'update') {

                $name      = $_POST['name'];
                $code      = strtoupper($_POST['code']);
                $teacher   = User::make($_POST['teacher']);
                $group     = Group::make($_POST['group']);
                $startTime = $_POST['starttime'] * 3600;
                $endTime   = $_POST['endtime'] * 3600;
                $dayofweek = $_POST['dayofweek'];

                $startDate = new Date;
                $endDate = new Date;
                $startDate->fromString(str_replace('/', '-', $_POST['startdate']));
                $endDate->fromString(str_replace('/', '-', $_POST['enddate']));

                try {

                    if ($_POST['method'] === 'add') {

                        $this->set('method-add', true);
                        $course = Course::make();
                        $course->create(
                            $name, 
                            $code,
                            $teacher,
                            $group,
                            $startDate,
                            $endDate,
                            $startTime,
                            $endTime,
                            $dayofweek
                        );
                        $this->flash->set(true, 'Cours ajouté !');
                        $this->set('method-add', false);

                    } else if ($_POST['method'] === 'update') {

                        $course = Course::make($_POST['edit']);
                        if ($course->exists()) {
                    
                            $course->validate(
                                $name, 
                                $code,
                                $teacher,
                                $group,
                                $startDate,
                                $endDate,
                                $startTime,
                                $endTime,
                                $dayofweek
                            );

                            $course->set([
                                'name'      => $name,
                                'code'      => $code,
                                'teacher'   => $teacher->getId(),
                                'group'     => $group->getId(),
                                'startdate' => $startDate->getTime(),
                                'enddate'   => $endDate->getTime(),
                                'starttime' => $startTime,
                                'endtime'   => $endTime,
                                'dayofweek' => $dayofweek,
                            ]);
                            
                            $course->save();
                            $this->flash->set(true, 'Cours modifié !');

                        }
                    }

                } catch (Exception $e) {
                    $this->flash->set(false, $e->getMessage());
                }

            } else if ($_POST['method'] === 'retrieve') {

                $course = Course::make($_POST['edit']);
                if ($course->exists()) {
                    $this->set('values', [
                        'course'    => $course,
                        'name'      => $course->get('name'),
                        'code'      => $course->get('code'),
                        'teacher'   => intval($course->get('teacher')),
                        'group'     => intval($course->get('group')),
                        'startdate' => date('Y-m-d', $course->get('startdate')),
                        'enddate'   => date('Y-m-d', $course->get('enddate')),
                        'starttime' => intval($course->get('starttime')),
                        'endtime'   => intval($course->get('endtime')),
                        'dayofweek' => intval($course->get('dayofweek')),
                    ]);
                } else {
                    $this->flash->set(false, 'Aucun cours sélectionné.');
                }

            } else if ($_POST['method'] === 'delete') {

                $course = Course::make($_POST['delete']);
                if ($course->exists()) {
                    $course->delete();
                    $this->flash->set(true, $course->get('name') . ' supprimé !');
                } else {
                    $this->flash->set(false, 'Aucun cours sélectionné.');
                }

            }

        }

        $list = User::make()->find([ 'permission' => User::TEACHER ]);
        $list = User::sort($list);

        $teachers = [];
        foreach ($list as $teacher) {
            $teachers[ strtoupper(substr($teacher->get('lastname'), 0, 1)) ][] = $teacher;
        }

        $this->set('teachers', $teachers);

        $groupList = Group::sort(Group::make()->find());
        $groups = [];

        foreach ($groupList as $group) {
            $groups[ 'P' . $group->getPromotion()->getYear() ][ $group->getIndex() ] = $group;
        }

        $this->set('groups', $groups);

        $promotions = Promotion::sort(Promotion::make()->find());
        $courses = [];

        foreach ($promotions as $promotion) {
            $subGroups = Group::sort($promotion->getGroups());
            foreach ($subGroups as $subGroup) {
                $courses[ 'P' . $promotion->getYear() . ' - G' . $subGroup->getIndex() ] = Course::sort($subGroup->getCourses());
            }
        }

        $this->set('courses', $courses);

    }

    public function user() {

        if (POST and isset($_POST['method'])) {

            if ($_POST['method'] === 'add' or $_POST['method'] === 'update') {

                $username   = $_POST['username'];
                $password   = $_POST['password'];
                $email      = $_POST['email'];
                $firstname  = $_POST['firstname'];
                $lastname   = $_POST['lastname'];
                $permission = intval($_POST['permission']);
                $group      = Group::make($_POST['group']);

                try {

                    if ($_POST['method'] === 'add') {

                        $this->set('method-add', true);
                        $user = User::make();
                        $user->create(
                            $username,
                            $password,
                            $email,
                            $firstname,
                            $lastname,
                            $permission,
                            $group
                        );
                        $this->flash->set(true, 'Utilisateur ajouté !');
                        $this->set('method-add', false);

                    } else if ($_POST['method'] === 'update') {

                        $user = User::make($_POST['edit']);
                        if ($user->exists()) {
                    
                            $user->validate(
                                $username === $user->get('username') ? null : $username,
                                $password ? $password : 'random',
                                $email,
                                $firstname,
                                $lastname,
                                $permission,
                                $group
                            );

                            $user->set([
                                'username'   => $username,
                                'email'      => $email,
                                'firstname'  => $firstname,
                                'lastname'   => $lastname,
                                'permission' => $permission,
                                'group'      => $group,
                            ]);

                            if ($password) {
                                $user->set('password', Factory::create(new Crypt)->createHash($password));
                            }
                            
                            $user->save();
                            $this->flash->set(true, 'Utilisateur modifié !');

                        }
                    }

                } catch (Exception $e) {
                    $this->flash->set(false, $e->getMessage());
                }

            } else if ($_POST['method'] === 'retrieve') {

                $user = User::make($_POST['edit']);
                if ($user->exists()) {
                    $this->set('values', [
                        'user' => $user,
                    ]);
                } else {
                    $this->flash->set(false, 'Aucun utilisateur sélectionné.');
                }

            } else if ($_POST['method'] === 'delete') {

                $user = User::make($_POST['delete']);
                if ($user->exists()) {
                    $user->delete();
                    $this->flash->set(true, $user->get('firstname') . ' ' . $user->get('lastname') . ' supprimé !');
                } else {
                    $this->flash->set(false, 'Aucun utilisateur sélectionné.');
                }

            }

        }

        $list = User::sort(User::make()->find());
        $users = [];

        foreach ($list as $user) {
            $users[ strtoupper(substr($user->get('lastname'), 0, 1)) ][] = $user;
        }

        $this->set('users', $users);

        $groupList = Group::sort(Group::make()->find());
        $groups = [];

        foreach ($groupList as $group) {
            $groups[ 'P' . $group->getPromotion()->getYear() ][ $group->getIndex() ] = $group;
        }

        $this->set('groups', $groups);

    }
    
}
