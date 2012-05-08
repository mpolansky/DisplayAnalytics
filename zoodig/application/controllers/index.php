<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	
        function __construct()
        {
            parent::__construct();
            $this->load->model('stat_model');
            
        }
        
    
	public function index()
	{
            
            //$this->session->set_userdata(array( 'celebrity_id'  => 1));
            
            // По умолчанию берем статистику за последние дни (недели)
            $table = 'week';
            $table_for_graph = 'day';
            $count_day = 7;
            $date = '2012-02-20';
            $data = array(
                       'tpl'                    => 'twitter',
                       'title'                  => 'Twitter',                       
                       'twits_for_period'       => $this->stat_model->get_twits_for_graph($table_for_graph, 1),
                       'retwits_for_period'     => $this->stat_model->get_retwits_for_graph($table_for_graph, 1),
                       'mentions_for_period'    => $this->stat_model->get_mentions_for_graph($table_for_graph, 1),
                       'xAxis_data'             => $this->stat_model->get_dayly_period($table, 1),
                       'sent_twits'             => $this->stat_model->get_count_twits($table, 1),
                       'twits_per_period'       => $this->stat_model->get_count_twits_per_period($table, 1),
                       'retwits'                => $this->stat_model->get_count_retwits($table, 1),
                       //'retwits_per_twit'   => $this->stat_model->get_count_retwits_per_twit($table, 1),
                       'replies'                => $this->stat_model->get_count_replies($table, 1),
                        'mentions'              => $this->stat_model->get_count_mentions($table, 1),
                       //'total_followers'    => $this->stat_model->get_total_followers($period, 1),
                       'follower_growth'        => $this->stat_model->get_follower_growth($table, 1),                        
                       //'follower_per_day'  => $this->stat_model->get_follower_per_period($table, 1), 
                       'count_day'              => $count_day,
                       'klout_score'            => $this->stat_model->get_klout_score_for_day($date, 1),
                       'top_twits'              => $this->stat_model->get_top_twits($table, 1),
                
                     );

       

            $this->load->view('main', $data);
            
           
	}
}
