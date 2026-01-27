<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ReservationModel;
use App\Models\LoginLog;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $res = new ReservationModel();
        $loginLogModel = new LoginLog();

        return view('admin/dashboard', [
            'total' => $res->countAll(),
            'pending' => $res->where('status','pending')->countAllResults(),
            'approved' => $res->where('status','approved')->countAllResults(),
            'declined' => $res->where('status','canceled')->countAllResults(),
            'reservations' => $res->findAll(),
            'logs' => $loginLogModel->findAll(),
            'page' => 'dashboard'
        ]);
    }

    public function manageReservations()
    {
        $model = new ReservationModel();

        return view('admin/manage-reservations', [
            'reservations' => $model->findAll(),
            'page' => 'manage-reservations'
        ]);
    }

    public function manageSK()
    {
        $userModel = new UserModel();

        $pending = $userModel->where('role', 'sk')->where('is_approved', 0)->findAll();
        $approved = $userModel->where('role', 'sk')->where('is_approved', 1)->findAll();
        $rejected = $userModel->where('role', 'sk')->where('is_approved', 2)->findAll();

        return view('admin/manage-sk', [
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'page' => 'manage-sk'
        ]);
    }

    public function approveSK()
    {
        $id = $this->request->getPost('id');
        if ($id) {
            (new UserModel())->update($id, ['is_approved' => 1]);
        }
        return redirect()->back()->with('success', 'SK account approved successfully!');
    }

    public function rejectSK()
    {
        $id = $this->request->getPost('id');
        if ($id) {
            (new UserModel())->update($id, ['is_approved' => 2]);
        }
        return redirect()->back()->with('success', 'SK account rejected successfully!');
    }

    public function setReservation()
    {
        return view('admin/set_reservation');
    }

    public function newReservation()
    {
        $resourceModel = new \App\Models\ResourceModel();
        $resources = $resourceModel->findAll();

        return view('admin/new-reservation', [
            'page' => 'manage-reservations',
            'resources' => $resources
        ]);
    }

    public function createReservation()
    {
        $request = $this->request;
        $reservationModel = new ReservationModel();

        $resourceId = $request->getPost('resource_id');
        $visitorType = $request->getPost('visitor_type');
        $userEmail = $request->getPost('user_email');
        $visitorName = $request->getPost('visitor_name');
        $visitorContactEmail = $request->getPost('visitor_contact_email');
        $reservationDate = $request->getPost('reservation_date');
        $reservationType = $request->getPost('reservation_type');
        $startTime = $request->getPost('start_time');
        $endTime = $request->getPost('end_time');
        $purpose = $request->getPost('purpose');
        $pcsJson = $request->getPost('pcs');
        $pcs = json_decode($pcsJson, true) ?? [];

        if (empty($pcs)) {
            return redirect()->back()->with('error', 'Please select at least one PC');
        }

        // Create reservation entry
        $data = [
            'user_id' => $userEmail,
            'visitor_type' => $visitorType,
            'visitor_name' => $visitorName,
            'visitor_email' => $visitorContactEmail,
            'reservation_date' => $reservationDate,
            'reservation_type' => $reservationType,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'purpose' => $purpose,
            'pc_numbers' => json_encode($pcs),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $reservationModel->insert($data);

        return redirect()->to('/admin/manage-reservations')->with('success', 'Reservation created successfully!');
    }

    public function approve()
    {
        $id = $this->request->getPost('id');
        if ($id) {
            (new ReservationModel())->update($id, ['status'=>'approved']);
        }
        return redirect()->back();
    }

    public function decline()
    {
        $id = $this->request->getPost('id');
        if ($id) {
            (new ReservationModel())->update($id, ['status'=>'declined']);
        }
        return redirect()->back();
    }

    public function scanner()
    {
        return view('admin/scanner');
    }

    public function loginLogs()
    {
        $loginLogModel = new LoginLog();
        return view('admin/login-logs', [
            'logs' => $loginLogModel->findAll(),
            'page' => 'login-logs'
        ]);
    }

    public function activityLogs()
    {
        return view('admin/activity-logs', [
            'page' => 'activity-logs'
        ]);
    }

    public function profile()
    {
        $user = (new UserModel())->find(session()->get('user_id'));
        return view('admin/profile',['user'=>$user]);
    }
}
