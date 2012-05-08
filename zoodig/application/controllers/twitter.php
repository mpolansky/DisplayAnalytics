<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twitter extends CI_Controller {

	
        function __construct()
        {
            parent::__construct();
            $this->load->model('stat_model');
            
        }
        
        public function days_graph()
        {
            $parametr = $_POST['parametr'];
            $stat_data = array();
            $date = '2012-02-20';
            
            if($_POST['select_id'] == 'periodSelect')
            {    
                $follower_growth = $this->stat_model->get_follower_growth_for_day($date, 1);
                $stat_data['follower_growth'] = $follower_growth['added_followers'];
                $stat_data['follower_growth_per_day'] = $follower_growth['added_followers']/1;

                $twits_sent = $this->stat_model->get_twits_for_day($date, 1);
                $stat_data['sent_twits'] = $twits_sent['sent_twits'];            
                $stat_data['twits_per_period'] = round($twits_sent['sent_twits']/1, 2);

                $retweets = $this->stat_model->get_retwits_for_day($date, 1);
                $stat_data['count_retwits'] = $retweets['retwits'];

                $replies = $this->stat_model->get_replies_for_day($date, 1);
                $stat_data['count_replies'] = $replies['replies'];

                $total_mentions = $this->stat_model->get_count_mentions_for_day($date, 1);
                $stat_data['total_mentions'] = $total_mentions['total_mentions'];

                $klout_score = $this->stat_model->get_klout_score_for_day($date, 1);
                $stat_data['klout_score'] = $klout_score['klout_score'];
                
                $t_tweets = $this->stat_model->get_top_twits_for_day($date, 1);
                //$mentions = $this->stat_model->get_mentions_for_graph_hourly($date, 1);
                    $top_tweets = array();
                    foreach($t_tweets as $ttw)
                    {
                        $top_tweets[] = $ttw;
                    }
                    $stat_data['top_tweets'] = $top_tweets;
            }
            
            switch ($parametr)
            {
                
                case 'mentions':
                case 'retweets':
                    
                    $twits = $this->stat_model->get_twits_for_graph_hourly($date, 1);
                    //print_r($twits); die();
                    $twit = array();
                    $xAxis = array();
                    for($i=0; $i<24; $i++)
                    {
                        $hour = ($i<10) ? "0".$i : $i;
                        $data = 0;
                        foreach($twits as $tw)
                        {                            
                            if($hour.":00" == $tw['hr'] && $data == 0) 
                            {
                               $data = intval($tw['twits']);                               
                            }                               
                            
                        }
                        
                        $twit[] = ($data>0) ? $data : 0;
                        $data = 0;
                        $xAxis[] = $hour.":00";
                        
                    }
                    $stat_data['twits'] = $twit;                    
                    $stat_data['xAxis'] = $xAxis;
                    
                    
                    $retwits = $this->stat_model->get_retwits_for_graph_hourly($date, 1);
                    $retwit = array();
                    foreach($retwits as $retw)
                    {
                        $retwit[] = intval($retw['retwits']);
                    }
                    $stat_data['retwits'] = $retwit;
                    
                    
                    
                    $mentions = $this->stat_model->get_mentions_for_graph_hourly($date, 1);
                    $mention = array();
                    foreach($mentions as $mnt)
                    {
                        $mention[] = intval($mnt['mentions']);
                    }
                    $stat_data['mentions'] = $mention;
                    

                    break;
                
                case 'totalFollowers':
                case 'followerAdded':
                    
                    $totalFollowers = $this->stat_model->get_follower_for_graph_hourly($date, 1);                    
                    $follower = array();
                    $xAxis = array();
                    foreach($totalFollowers as $tf)
                    {
                        $follower[] = intval($tf['total_followers']);
                        $xAxis[] = $tf['hr'];
                    }    
                    $stat_data['followers'] = $follower;                    
                    
                    $stat_data['xAxis'] = $xAxis;
                    
                   /* 
                    $addedFollowers = $this->stat_model->get_addedfollower_for_graph_hourly($period, 1);                    
                    $addedfollower = array();
                    foreach($addedFollowers as $af)
                    {
                        $addedfollower[] = intval($af['added_followers']);
                    }    
                    $points['addedfollowers'] = $addedfollower;
                  */  
                    
                   

                    break;

                default:
                    break;
            }
            
            
            
          echo json_encode($stat_data);  
            
        }        








        public function weeks_graph()
	{
            $period = $_POST['period'];
            $parametr = $_POST['parametr'];
            
            switch ($_POST['period'])
            {
                case 'week':
                    $table = 'week';
                    $table_for_graph = 'day';
                    $count_day = 7;
                    $date = '2012-02-26';

                    break;

                default:
                    break;
            }
            
            
            
            $stat_data = array();
            
            
            if($_POST['select_id'] == 'periodSelect')
            {
                // здесь еще запросы для получения статистики, отображаемой в таблице
                $follower_growth = $this->stat_model->get_follower_growth($table, 1);
                $stat_data['follower_growth'] = $follower_growth['added_followers'];
                $stat_data['follower_growth_per_day'] = round($follower_growth['added_followers']/$count_day, 0);


                $twits_sent = $this->stat_model->get_count_twits($table, 1);
                $stat_data['sent_twits'] = $twits_sent['sent_twits'];            

                $twits_per_period = $this->stat_model->get_count_twits_per_period($table, 1);
                $stat_data['twits_per_period'] = round($twits_per_period['twits_per_period']/$count_day, 2);
                //echo $stat_data['twits_per_period']; die();

                $retweets = $this->stat_model->get_count_retwits($table, 1);
                $stat_data['count_retwits'] = $retweets['retwits'];

                $replies = $this->stat_model->get_count_replies($table, 1);
                $stat_data['count_replies'] = $replies['replies'];

                $total_mentions = $this->stat_model->get_count_mentions($table, 1);
                $stat_data['total_mentions'] = $total_mentions['total_mentions'];

                $klout_score = $this->stat_model->get_klout_score_for_day($date, 1);
                $stat_data['klout_score'] = $klout_score['klout_score'];
                
                
            }
            
            
            $xAxis_data = $this->stat_model->get_dayly_period($table, 1);
            $xAxis = array();
            foreach($xAxis_data as $xax)
            {
                $xAxis[] = $xax['date'];
            }
            $stat_data['xAxis'] = $xAxis;
            
            
            
            switch ($parametr)
            {
                case 'mentions':
                case 'retweets':
                    
                    $twits = $this->stat_model->get_twits_for_graph($table_for_graph, 1);
                    $twit = array();
                    foreach($twits as $tw)
                    {
                        $twit[] = intval($tw['twits']);
                    }    
                    $stat_data['twits'] = $twit;                  
                    
                    
                    $retwits = $this->stat_model->get_retwits_for_graph($table_for_graph, 1);
                    $retwit = array();
                    foreach($retwits as $retw)
                    {
                        $retwit[] = intval($retw['retwits']);
                    }
                    $stat_data['retwits'] = $retwit;
                    
                    
                    $mentions = $this->stat_model->get_mentions_for_graph($table_for_graph, 1);
                    $mention = array();
                    foreach($mentions as $mnt)
                    {
                        $mention[] = intval($mnt['mentions']);
                    }
                    $stat_data['mentions'] = $mention;
                    
                    
                    
                    
                    
                    break;
                
                case 'totalFollowers':
                case 'followerAdded':
                    
                    $totalFollowers = $this->stat_model->get_follower_for_graph($table_for_graph, 1);                    
                    $follower = array();
                    foreach($totalFollowers as $tf)
                    {
                        $follower[] = intval($tf['total_followers']);
                    }    
                    $stat_data['followers'] = $follower;
                    
                    
                    
                    $addedFollowers = $this->stat_model->get_addedfollower_for_graph($table_for_graph, 1);                    
                    $addedfollower = array();
                    foreach($addedFollowers as $af)
                    {
                        $addedfollower[] = intval($af['added_followers']);
                    }    
                    $stat_data['addedfollowers'] = $addedfollower;
                    
                    
                  /*  
                    $xAxis_data = $this->stat_model->get_dayly_period($table, 1);
                    $xAxis = array();
                    foreach($xAxis_data as $xax)
                    {
                        $xAxis[] = $xax['date'];
                    }
                    $points['xAxis'] = $xAxis;
                  */  

                    break;
                
                default:
                    break;
            }
            
                   
            echo json_encode($stat_data);
            
           
	}
        
}
