<div id="selectBoxes">
    <select id="statSelect">
        <option value="retweets">Retweets</option>
        <option value="mentions">Mentions</option>
        <option value="totalFollowers">Total Followers</option>
        <option value="followerAdded">Follower Growth</option>
        
    </select>

    <select id="periodSelect">
        <option value="month">Month</option>
        <option value="week" selected="selected">Week</option>
        <option value="day">Day</option>
        <option value="hour">Hour</option>
        <option value="custom">Custom</option>
    </select>
</div>
<div id="statData">
    <div id="social_logo">
        <img src="img/tweeter_active.jpg" />
        <img src="img/facebook.jpg" />
    </div>
    
    <div class="blackTitle">Twitter Rank</div>
    <table id="rankTable">
        <tr>
            <th>All Athletes</th>
            <th>League</th>
            <th>Position</th>
            <th>Team</th>
        </tr>
        <tr>
            <td>23</td>
            <td>15</td>
            <td>3</td>
            <td>2</td>
        </tr>
    </table>
    <script type="text/javascript" src="/js/themes/grid.js"></script>
    <script>
    /*    var chart1; // globally available
        $(document).ready(function() {
            chart1 = new Highcharts.StockChart({
                chart: {
                    renderTo: 'container'
                },
                rangeSelector: {
                    selected: 1
                },
                series: [{
                    name: 'USD to EUR',
                    data: usdtoeur // predefined JavaScript array
                }]
            });
        });
      */  
        
        var chart;
        $(document).ready(function() {
                
                coordinates = new Object();
                coordinates.tweets = [<?php foreach ($twits_for_period as $count_twit)
                            {
                                echo $count_twit['twits'].".0,";
                            }?>];
                coordinates.retweets = [<?php foreach ($retwits_for_period as $count_retwit)
                            {
                                echo $count_retwit['retwits'].".0,";
                            }?>];
                coordinates.mentions = [<?php foreach ($mentions_for_period as $count_mentions)
                            {
                                echo $count_mentions['mentions'].".0,";
                            }?>];
                
                coordinates.xAxis = [<?php foreach ($xAxis_data as $xax)
                            {
                                echo "'".$xax['date']."',";
                            }?>];
                                            
                var twitsRetwitsMentions = [{
                                name: 'Tweets Sent',
                                color: '#4572A7',
                                type: 'column',
                                yAxis: 1,
                                data: coordinates.tweets

                        }, {
                                name: 'Retweets',                                
                                color: '#C13737',
                                type: 'line',
                                data: coordinates.retweets,
                                marker: {
                                    enabled: false
                                }
                        }, {
                                name: 'Mentions',
                                color: '#F09748',
                                type: 'line',
                                data: coordinates.mentions,
                                marker: {
                                    enabled: false
                                }
                        }];
                    
                    
                             
                printChart(twitsRetwitsMentions, coordinates.xAxis);
                
            
                
            
            $('#statSelect, #periodSelect').change(function(){
                period = $('#periodSelect').val();
                parametr = $('#statSelect').val();
                thisSelect = $(this).attr('id'); 
                
                $.ajax({
                        type: 'POST',
                        url: 'index.php/twitter/'+period+'s_graph',
                        data: ({parametr: parametr, period:period, select_id:thisSelect}),
                        dataType: 'text json',
                        success: function(statdata){
                           
                        if(thisSelect == 'periodSelect')
                        {    
                            $('#follower_growth span.paramValue').text(statdata.follower_growth);
                            $('#follower_growth_per_day span.paramValue').text(statdata.follower_growth_per_day);
                            $('#tweets_sent span.paramValue').text(statdata.sent_twits);
                            //$('#tweetsSentPerPeriod span.paramName').text('Tweets Sent Per '+period);
                            $('#tweets_sent_per_period span.paramValue').text(statdata.twits_per_period);
                            $('#retweets span.paramValue').text(statdata.count_retwits);
                            retweets_per_tweet = (statdata.count_retwits/statdata.sent_twits).toFixed(0);
                            $('#retweets_per_tweet span.paramValue').text(retweets_per_tweet);
                            $('#replies span.paramValue').text(statdata.count_replies);
                            $('#total_mentions span.paramValue').text(statdata.total_mentions);
                            
                            top_tweets =    '<tr>\n\
                                                <th>Rank</th>\n\
                                                <th>Tweet</th>\n\
                                                <th>Retweet</th>\n\
                                                <th>Reply</th>\n\
                                            </tr>';
                            for(i = 0; i < statdata.top_tweets.length; i++)
                            {
                                number = i + 1;
                                top_tweets += '<tr>\n\
                                                <td>'+number+'</td>\n\
                                                <td class="twitTextTd">'+statdata.top_tweets[i].body+'. Posted '+statdata.top_tweets[i].date+'</td>\n\
                                                <td>'+statdata.top_tweets[i].count_retweets+'</td>\n\
                                                <td>'+statdata.top_tweets[i].count_replies+'</td>\n\
                                            </tr>';
                            }
                            $('#topTweetTable').empty().append(top_tweets);
                            
                        }    
                            //alert(coordinates.xAxis);
                            xAxis = statdata.xAxis;
                            if(parametr == 'retweets' || parametr == 'mentions')
                            {
                                
                                settings = [
                                    {
                                        name: 'Tweets Sent',
                                        color: '#4572A7',
                                        type: 'column',
                                        yAxis: 1,
                                        data: statdata.twits

                                    }, 
                                    {
                                        name: 'Retweets',                                
                                        color: '#C13737',
                                        type: 'line',
                                        data: statdata.retwits,
                                        marker: 
                                        {
                                            enabled: false
                                        }
                                    }, 
                                    {
                                        name: 'Mentions',
                                        color: '#F09748',
                                        type: 'line',
                                        data: statdata.mentions,
                                        marker: 
                                        {
                                            enabled: false
                                        }
                                }]; 
                                
                               
                                
                            }
                            if(parametr == 'totalFollowers' || parametr == 'followerAdded')
                            {
                                settings = [
                                    {
                                        name: 'Followers',
                                        color: '#F09748',
                                        type: 'line',
                                        yAxis: 1,
                                        data: statdata.followers,
                                        marker: 
                                        {
                                            enabled: false
                                        }
                                    },
                                    {
                                        name: 'Followers Added',
                                        color: '#4572A7',
                                        type: 'line',
                                        
                                        data: statdata.addedfollowers,                                        
                                        marker: 
                                        {
                                            enabled: false
                                        }    
                                    }
                            ];                                
                            }
                            printChart(settings, xAxis);
                        }
                });
                
            });
            
            
            
            
        });
        
        function printChart(settings, categories){
            //alert(categories);
            chart = new Highcharts.Chart({
                        chart: {
                                renderTo: 'container',
                                zoomType: 'xy'
                        },
                        title: {
                                text: 'Twitter Account Activity and Engagment',
                                style: {
                                                color: '#000'
                                        }
                        },
                        
                        xAxis: [{
                                categories: categories,/*['1/16', '1/17', '1/18', '1/19', '1/20', '1/21', '1/22'],*/
                                labels: {
                                    rotation: -45,
                                    align: 'right',
                                    style: {
                                            font: 'bold 11px Arial'
                                    }
                                }
                        }],
                        yAxis: [{ // Primary yAxis
                                labels: {
                                        formatter: function() {
                                                return this.value;
                                        },
                                        style: {
                                                color: '#000'
                                        }
                                },
                                title: {
                                        text: '',
                                        style: {
                                                color: '#000'
                                        }
                                },
                                min: 0
                        }, { // Secondary yAxis
                                title: {
                                        text: '',
                                        style: {
                                                color: '#000'
                                        }
                                },
                                labels: {
                                        formatter: function() {
                                                return this.value;
                                        },
                                        style: {
                                                color: '#000'
                                        }
                                },
                                opposite: true
                        }],
                        tooltip: {
                                formatter: function() {
                                        return ''+
                                                this.x +': '+ this.y +
                                                (this.series.name == 'Tweets Sent' ? ' ' : '');
                                }
                        },
                        plotOptions: {
                                line: {
                                        lineWidth: 5,
                                        marker: {
                                                enabled: false
                                        }
                                }
                        },
                        legend: {
                                layout: 'horizontal',
                                align: 'center',
                                x: -150,
                                verticalAlign: 'top',
                                y: 25,
                                floating: false,
                                backgroundColor: '#EEEEEE',
                                borderWidth: 0
                        },
                        series: settings/*[{
                                name: 'Tweets Sent',
                                color: '#4572A7',
                                type: 'column',
                                yAxis: 1,
                                data: coordinates.tweets

                        }, {
                                name: 'Retweets',                                
                                color: '#C13737',
                                type: 'line',
                                data: coordinates.retweets,
                                marker: {
                                    enabled: false
                                }
                        }, {
                                name: 'Mentions',
                                color: '#F09748',
                                type: 'line',
                                data: coordinates.mentions,
                                marker: {
                                    enabled: false
                                }
                        }] */
                                
                });
        
        
        
        
        
        }
        
    </script>
    
    <div id="container" style="width: 100%; height: 300px"></div>
    
    
   <?php /*print_r($twits);*/?>
    <div class="blackTitle">Twitter Summary Statistics</div>
    <div id="summaryBlock">
        <div id="leftsummaryBlock">
            <div id="follower_growth" class="summaryBlock"><span class="paramName">Follower Growth</span><span class="paramValue"><?php echo (!empty($follower_growth['added_followers']))? $follower_growth['added_followers'] : 0;?></span></div>
            <div id="follower_growth_per_day" class="summaryBlock"><span class="paramName">Follower Per Day</span><span class="paramValue"><?php echo (!empty($follower_growth['added_followers']))? round($follower_growth['added_followers']/$count_day, 0) : 0;?></span></div>
            <div id="tweets_sent" class="summaryBlock"><span class="paramName">Tweets Sent</span><span class="paramValue"><?php echo (!empty($sent_twits['sent_twits']))? $sent_twits['sent_twits'] : 0;?></span></div>
            <div id="tweets_sent_per_period" class="summaryBlock"><span class="paramName">Tweets Sent Per Day</span><span class="paramValue"><?php echo (!empty($twits_per_period['twits_per_period']))? round($twits_per_period['twits_per_period']/$count_day,2) : 0;?></span></div>
            <div id="retweets" class="summaryBlock"><span class="paramName">Retweets</span><span class="paramValue"><?php echo (!empty($retwits['retwits']))? $retwits['retwits'] : 0;?></span></div>
            <div id="retweets_per_tweet" class="summaryBlock"><span class="paramName">Retweets Per Tweet</span><span class="paramValue"><?php echo (!empty($sent_twits['sent_twits']) && !empty($retwits['retwits']))? round($retwits['retwits']/$sent_twits['sent_twits'], 0) : 0;?></span></div>
            
        </div>
        <div id="rightsummaryBlock">
            <div id="replies" class="summaryBlock"><span class="paramName">Replies</span><span class="paramValue"><?php echo (!empty($replies['replies']))? $replies['replies'] : 0;?></span></div>
            <div id="total_mentions" class="summaryBlock"><span class="paramName">Total Mentions</span><span class="paramValue"><?php echo (!empty($mentions['total_mentions']))? $mentions['total_mentions'] : 0;?></span></div>
            <div class="summaryBlock"><span class="paramName">Potential Impression</span><span class="paramValue"><?php echo (!empty($potential_impression))? $potential_impression : 0;?></span></div>
            <div class="summaryBlock"><span class="paramName">Total Interaction</span><span class="paramValue"><?php echo (!empty($total_interaction))? $total_interaction : 0;?></span></div>
            <div class="summaryBlock"><span class="paramName">Largest Local of Mentions</span><span class="paramValue"><?php echo (!empty($largest_local_mentions))? $largest_local_mentions : 0;?></span></div>
            <div class="summaryBlock"><span class="paramName">Klout Score</span><span class="paramValue"><?php echo (!empty($klout_score['klout_score']))? $klout_score['klout_score'] : 0;?></span></div>
        </div>

    </div>
            
        
    
    <div style="clear: both;"></div>
    <div class="blackTitle">Top tweets</div>
    <table id="topTweetTable">
        <tr>
            <th>Rank</th>
            <th>Tweet</th>
            <th>Retweet</th>
            <th>Reply</th>
        </tr>
        <?php 
            foreach ($top_twits as $key => $top_tw)
            {
                $number = $key+1;
                echo    '<tr>
                            <td>'.$number.'</td>
                            <td class="twitTextTd">'.$top_tw['body'].'.Posted '.$top_tw['date'].'</td>
                            <td>'.$top_tw['count_retwits'].'</td>
                            <td>'.$top_tw['count_replies'].'</td>
                        </tr>';
            }    
        ?>
        
        
    </table>    
</div>    
<?php


?>
