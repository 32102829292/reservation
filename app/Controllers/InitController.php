$session = session();

if ($session->has('user_id')) {

    $lastActivity = $session->get('last_activity');
    $now = time();

    if ($lastActivity && ($now - $lastActivity) > $this->maxIdleTime) {

        // FORCE LOGOUT
        $db = \Config\Database::connect();

        $activeLog = $db->table('login_logs')
            ->where('user_id', $session->get('user_id'))
            ->where('logout_time', null)
            ->orderBy('login_time', 'DESC')
            ->get()
            ->getRowArray();

        if ($activeLog) {
            $db->table('login_logs')
                ->where('id', $activeLog['id'])
                ->update([
                    'logout_time' => date('Y-m-d H:i:s')
                ]);
        }

        $session->destroy();
        return redirect()->to('/login')->send();
    }

    // update activity timestamp
    $session->set('last_activity', $now);
}
