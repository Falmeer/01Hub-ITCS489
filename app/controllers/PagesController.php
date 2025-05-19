<?php

class PagesController extends Controller {
    public function about() {
        $this->view('pages/about');
    }

    public function home() {
        // optional homepage
        $this->view('pages/home');
    }
    public function landing()
    {
        $this->view('pages/landing');
    }
}