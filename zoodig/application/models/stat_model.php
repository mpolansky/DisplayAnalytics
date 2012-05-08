<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payment_model
 *
 * @author fox5
 */
class stat_model extends Base
{

    //put your code here
    function __construct()
    {
        parent::__construct();
    }

    
    // Статистика для отображения в таблицах
        
    
    /* В таблицах dayly weekly monthly yearly*/
    function get_total_followers($table, $twit_celebrities_id)
    {
        $sql = "SELECT total_followers
                FROM ".$table."ly_count_followers
                WHERE twit_user_id = ".$twit_celebrities_id." AND 
                    date = (SELECT MAX(date) FROM ".$table."ly_count_followers)";

        return $this->db->query($sql)->row_array();
    }        

    function get_follower_growth($table, $twit_celebrities_id)
    {
        $sql = "SELECT sum(added_followers) as added_followers
                FROM ".$table."ly_count_followers
                WHERE twit_user_id = ".$twit_celebrities_id;

        return $this->db->query($sql)->row_array(); 
    }
   /* 
    function get_follower_per_period($table, $twit_celebrities_id)
    {        
        $sql = "SELECT sum(added_followers)/(
                        (SELECT MAX(".$table."_number) FROM ".$table."ly_count_followers WHERE twit_user_id = 1) - 
                        (SELECT MIN(".$table."_number) FROM ".$table."ly_count_followers WHERE twit_user_id = 1)+1) 
                          as follower_per_period
                FROM ".$table."ly_count_followers
                WHERE twit_user_id = ".$twit_celebrities_id;

        return $this->db->query($sql)->row_array(); 
    }
   */ 

    function get_count_twits($table, $twit_celebrities_id)
    {
        
        $sql = "SELECT sum(count_twits) as sent_twits
                FROM ".$table."ly_count_twits
                WHERE twit_user_id = ".$twit_celebrities_id;

        return $this->db->query($sql)->row_array();
    }
    
    function get_count_twits_per_period($table, $twit_celebrities_id)
    {
        $sql = "SELECT sum(count_twits)/(
                        (SELECT MAX(".$table."_number) FROM ".$table."ly_count_twits WHERE twit_user_id = 1) -  
                        (SELECT MIN(".$table."_number) FROM ".$table."ly_count_twits WHERE twit_user_id = 1)+1) 
                         as twits_per_period
                FROM ".$table."ly_count_twits
                WHERE twit_user_id = ".$twit_celebrities_id;

        return $this->db->query($sql)->row_array();
    }
    
    function get_count_retwits($table, $twit_celebrities_id)
    {
        $sql = "SELECT sum(count_retwits) as retwits
                FROM ".$table."ly_count_retwits
                WHERE twit_user_id = ".$twit_celebrities_id;

        return $this->db->query($sql)->row_array();
    }
   /* 
    function get_count_retwits_per_twit($table, $twit_celebrities_id)
    {
        
        $sql = "SELECT sum(count_retwits) as count_retwits
                FROM ".$table."ly_count_retwits
                WHERE twit_user_id = ".$twit_celebrities_id;
        
        $count_retwits = $this->db->query($sql)->row_array();
        
        $sql2 = "SELECT sum(count_twits) as count_twits
                FROM ".$table."ly_count_twits
                WHERE twit_user_id = ".$twit_celebrities_id;
        
        $count_twits = $this->db->query($sql2)->row_array();
        
        return $count_retwits['count_retwits']/$count_twits['count_twits'];
        
    }
*/
    function get_count_replies($table, $twit_celebrities_id)
    {
        $sql = "SELECT sum(count_replies) as replies
                FROM ".$table."ly_count_replies
                WHERE twit_user_id = ".$twit_celebrities_id;

        return $this->db->query($sql)->row_array();
    }
    
    function get_count_mentions($table, $twit_celebrities_id)
    {
        $sql = "SELECT sum(count_mentions) as total_mentions
                FROM ".$table."ly_count_mentions
                WHERE twit_user_id = ".$twit_celebrities_id;

        return $this->db->query($sql)->row_array();
    }
    
    function get_top_twits($table, $twit_celebrities_id)
    {
        $sql = "SELECT body, date, count_replies, count_retwits
                FROM ".$table."ly_top_twits_by_reply_and_retwits
                WHERE twit_user_id = ".$twit_celebrities_id." 
                ORDER BY (count_retwits+count_replies) desc";

        return $this->db->query($sql)->result_array();
    }





    /* Для формирования на "лету"*/
    function get_twits_for_day($date, $twit_celebrities_id)
    {
        $sql = "SELECT COUNT(twit_id) as sent_twits
                FROM sent
                WHERE DATE(`time`) = '".$date."' AND username = (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id.")";

        return $this->db->query($sql)->row_array();
    }

    function get_retwits_for_day($date, $twit_celebrities_id)
    {
        /*$sql = "SELECT COUNT(twit_id) as sent_twits
                FROM sent
                WHERE DATE(`time`) = '".$date."' AND username = (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id.")";
        */
        $sql = "SELECT count(`Retweet ID`) as retwits
                FROM mentions 
                WHERE `Retweet ID` IS NOT NULL AND DATE(`time`) = '".$date."' AND `Account Mentioned` = CONCAT('@', (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id."))";
        
        return $this->db->query($sql)->row_array();
    }

    function get_follower_growth_for_day($date, $twit_celebrities_id)
    {
        $sql = "SELECT MAX(followers), (MAX(followers)-MIN(followers)) as added_followers
                FROM sent 
                WHERE DATE(`time`) = '".$date."' AND username = (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id.")";
        
        return $this->db->query($sql)->row_array();
    }

    function get_replies_for_day($date, $twit_celebrities_id)
    {
        $sql = "SELECT count(`Reply to ID`) as replies
                FROM mentions 
                WHERE `Reply to ID` IS NOT NULL AND DATE(`time`) = '".$date."' AND `Account Mentioned` = CONCAT('@', (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id."))";
        
        return $this->db->query($sql)->row_array();
    }
    
    function get_count_mentions_for_day($date, $twit_celebrities_id)
    {
        $sql = "SELECT count(`Mention_id`) as total_mentions
                FROM mentions 
                WHERE DATE(`time`) = '".$date."' AND `Account Mentioned` = CONCAT('@', (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id."))";
        
        return $this->db->query($sql)->row_array();
    }
    
    function get_klout_score_for_day($date, $twit_celebrities_id)
    {
        $sql = "SELECT klout_score
                FROM sent 
                WHERE DATE(`time`) = '".$date."' AND username = (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id.")";
        
        return $this->db->query($sql)->row_array();
    }

    //Данные для графика
    function get_twits_for_graph($table, $twit_celebrities_id)
    {//, DATE_FORMAT(date, '%m/%e/%Y') as date
        $sql = "SELECT count_twits as twits
                FROM ".$table."ly_count_twits
                WHERE twit_user_id = ".$twit_celebrities_id."
                 ORDER BY ".$table."_number";

        return $this->db->query($sql)->result_array();
    }
    
    function get_dayly_period($table, $twit_celebrities_id)
    {
        $sql = "SELECT DATE_FORMAT(date, '%m/%e/%Y') as date
                FROM dayly_count_mentions
                WHERE twit_user_id = ".$twit_celebrities_id;
        return $this->db->query($sql)->result_array();
    }        
    
    function get_retwits_for_graph($table, $twit_celebrities_id)
    {
        $sql = "SELECT count_retwits as retwits
                FROM ".$table."ly_count_retwits
                WHERE twit_user_id = ".$twit_celebrities_id;

        return $this->db->query($sql)->result_array();
    }
    
    function get_mentions_for_graph($table, $twit_celebrities_id)
    {
        $sql = "SELECT count_mentions as mentions
                FROM ".$table."ly_count_mentions
                WHERE twit_user_id = ".$twit_celebrities_id;
        return $this->db->query($sql)->result_array();
    }
    
    function get_follower_for_graph($table, $twit_celebrities_id)
    {        
        $sql = "SELECT total_followers 
                FROM ".$table."ly_count_followers
                WHERE twit_user_id = ".$twit_celebrities_id." ORDER BY ".$table."_number";

        return $this->db->query($sql)->result_array();
    }
    
    function get_addedfollower_for_graph($table, $twit_celebrities_id)
    {        
        $sql = "SELECT added_followers 
                FROM ".$table."ly_count_followers
                WHERE twit_user_id = ".$twit_celebrities_id." ORDER BY ".$table."_number";

        return $this->db->query($sql)->result_array();
    }
    
    /* При формировании на "лету"*/
    function get_twits_for_graph_hourly($date, $twit_celebrities_id)
    {//, DATE_FORMAT(date, '%m/%e/%Y') as date
        $sql = "SELECT DATE_FORMAT(`time`, '%H:00') as hr, count(twit_id) as twits
                FROM sent 
                WHERE DATE(`time`) = '".$date."' AND username = (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id.") 
                GROUP BY hr 
                ORDER BY hr";

        return $this->db->query($sql)->result_array();
    }
    
    function get_retwits_for_graph_hourly($date, $twit_celebrities_id)
    {
        $sql = "SELECT DATE_FORMAT(`time`, '%H:00') as hr, count(`Retweet ID`) as retwits
                FROM mentions 
                WHERE DATE(`time`) = '".$date."' AND `Account Mentioned` = CONCAT('@', (SELECT username FROM celebrities WHERE twitter_user_id  = 1))
                 GROUP BY hr 
                 ORDER BY hr";
        
        return $this->db->query($sql)->result_array();
    }
    
    function get_mentions_for_graph_hourly($date, $twit_celebrities_id)
    {
        $sql = "SELECT DATE_FORMAT(`time`, '%H:00') as hr, count(`Mention_id`) as mentions
                FROM mentions 
                WHERE DATE(`time`) = '".$date."' AND `Account Mentioned` = CONCAT('@', (SELECT username FROM celebrities WHERE twitter_user_id  = 1))
                 GROUP BY hr 
                 ORDER BY hr";
        
        return $this->db->query($sql)->result_array();
    }
    
    function get_follower_for_graph_hourly($date, $twit_celebrities_id)
    {
        $sql = "SELECT DATE_FORMAT(`time`, '%H:00') as hr, followers as total_followers
                FROM sent 
                WHERE DATE(`time`) = '".$date."' AND username = (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id.") 
                GROUP BY hr 
                ORDER BY hr";

        return $this->db->query($sql)->result_array();
    }
    
    function get_top_twits_for_day($date, $twit_celebrities_id)
    {
        $sql = "SELECT sent.twit_id, body, DATE(sent.`time`) as date, count_retweets_table.count_retweets, count_replies_table.count_replies
                FROM sent, 
                    (SELECT twit_id, count(retweets_mentions.`Mention_id`) as count_retweets
                        FROM sent
                        LEFT JOIN mentions as retweets_mentions ON sent.twit_id = retweets_mentions.`Retweet ID`
                        WHERE sent.username = (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id.")
                        AND DATE(sent.`time`) = '".$date."' 
 
                        GROUP BY twit_id) as count_retweets_table, 
                    (SELECT twit_id, count(replies_mentions.`Mention_id`) as count_replies
                        FROM sent
                        LEFT JOIN mentions replies_mentions ON sent.twit_id = replies_mentions.`Reply to ID`
                        WHERE sent.username = (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id.")
                        AND DATE(sent.`time`) = '".$date."'
                        
                        GROUP BY twit_id) as count_replies_table
                WHERE sent.username = (SELECT username FROM celebrities WHERE twitter_user_id  = ".$twit_celebrities_id.")
                AND DATE(sent.`time`) = '".$date."'
                AND sent.twit_id = count_retweets_table.twit_id
                AND sent.twit_id = count_replies_table.twit_id
                ORDER BY (count_retweets_table.count_retweets+count_replies_table.count_replies) desc 
                LIMIT 10";

        return $this->db->query($sql)->result_array();
    }
}
?>

