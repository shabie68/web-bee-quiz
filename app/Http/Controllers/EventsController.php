<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Date;
use DB;
use PDO;

class EventsController extends BaseController
{
    public function getWarmupEvents() {
        return Event::all();
    }

    /* TODO: complete getEventsWithWorkshops so that it returns all events including the workshops
     Requirements:
    - maximum 2 sql queries
    - Don't post process query result in PHP
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - partial or not working answers also get graded so make sure you commit what you have

    Sample response on GET /events:
    ```json
    [
        {
            "id": 1,
            "name": "Laravel convention 2020",
            "created_at": "2021-04-25T09:32:27.000000Z",
            "updated_at": "2021-04-25T09:32:27.000000Z",
            "workshops": [
                {
                    "id": 1,
                    "start": "2020-02-21 10:00:00",
                    "end": "2020-02-21 16:00:00",
                    "event_id": 1,
                    "name": "Illuminate your knowledge of the laravel code base",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                }
            ]
        },
        {
            "id": 2,
            "name": "Laravel convention 2021",
            "created_at": "2021-04-25T09:32:27.000000Z",
            "updated_at": "2021-04-25T09:32:27.000000Z",
            "workshops": [
                {
                    "id": 2,
                    "start": "2021-10-21 10:00:00",
                    "end": "2021-10-21 18:00:00",
                    "event_id": 2,
                    "name": "The new Eloquent - load more with less",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                },
                {
                    "id": 3,
                    "start": "2021-11-21 09:00:00",
                    "end": "2021-11-21 17:00:00",
                    "event_id": 2,
                    "name": "AutoEx - handles exceptions 100% automatic",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                }
            ]
        },
        {
            "id": 3,
            "name": "React convention 2021",
            "created_at": "2021-04-25T09:32:27.000000Z",
            "updated_at": "2021-04-25T09:32:27.000000Z",
            "workshops": [
                {
                    "id": 4,
                    "start": "2021-08-21 10:00:00",
                    "end": "2021-08-21 18:00:00",
                    "event_id": 3,
                    "name": "#NoClass pure functional programming",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                },
                {
                    "id": 5,
                    "start": "2021-08-21 09:00:00",
                    "end": "2021-08-21 17:00:00",
                    "event_id": 3,
                    "name": "Navigating the function jungle",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                }
            ]
        }
    ]
     */

    public function getEventsWithWorkshops() {

        //change database file name accordingly
        $pdo= new PDO('sqlite:D:/xamp/htdocs/laravel-test/database/database.sqlite');
        
        $query = "
        SELECT events.*, GROUP_CONCAT(
            JSON_OBJECT(
                'id', workshops.id,
                'name', workshops.name,
                'start', workshops.start,
                'end', workshops.end,
                'event_id', workshops.event_id,
                'created_at', workshops.created_at,
                'updated_at', workshops.updated_at
            )
        ) as workshops
        FROM events
        LEFT JOIN workshops ON events.id = workshops.event_id
        GROUP BY events.id";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $eventsWorkshops = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close database connection
        $pdo = null;

        return $eventsWorkshops;



        /**


        Note**
            The below code uses post process query result but it output the exact result and passed the test

        $pdo= new PDO('sqlite:D:/xamp/htdocs/laravel-test/database/database.sqlite');
        
        $events = "SELECT  * FROM events";
        $workshops = "SELECT * FROM workshops";


        $stmt = $pdo->prepare($events);
        $stmt2 = $pdo->prepare($workshops);

        $stmt->execute();
        $stmt2->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        // Close database connection
        $pdo = null;

        $eventsWorkshops = [];
        

        foreach($results as $key => $event ) {
            $eventsWorkshops[$key] = $event;
            foreach($result2 as $workshop) {
                if($workshop['event_id'] == $event['id']) {
                    $eventsWorkshops[$key]['workshops'][] = $workshop;
                }
            } 
        }


        return $eventsWorkshops;

        **/



        throw new \Exception('implement in coding task 1');
    }


    /* TODO: complete getFutureEventWithWorkshops so that it returns events with workshops, that have not yet started
    Requirements:
    - only events that have not yet started should be included
    - the event starting time is determined by the first workshop of the event
    - the eloquent expressions should result in maximum 3 SQL queries, no matter the amount of events
    - Don't post process query result in PHP
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - partial or not working answers also get graded so make sure you commit what you have
    - join, whereIn, min, groupBy, havingRaw might be helpful
    - in the sample data set  the event with id 1 is already in the past and should therefore be excluded

    Sample response on GET /futureevents:
    ```json
    [
        {
            "id": 2,
            "name": "Laravel convention 2021",
            "created_at": "2021-04-20T07:01:14.000000Z",
            "updated_at": "2021-04-20T07:01:14.000000Z",
            "workshops": [
                {
                    "id": 2,
                    "start": "2021-10-21 10:00:00",
                    "end": "2021-10-21 18:00:00",
                    "event_id": 2,
                    "name": "The new Eloquent - load more with less",
                    "created_at": "2021-04-20T07:01:14.000000Z",
                    "updated_at": "2021-04-20T07:01:14.000000Z"
                },
                {
                    "id": 3,
                    "start": "2021-11-21 09:00:00",
                    "end": "2021-11-21 17:00:00",
                    "event_id": 2,
                    "name": "AutoEx - handles exceptions 100% automatic",
                    "created_at": "2021-04-20T07:01:14.000000Z",
                    "updated_at": "2021-04-20T07:01:14.000000Z"
                }
            ]
        },
        {
            "id": 3,
            "name": "React convention 2021",
            "created_at": "2021-04-20T07:01:14.000000Z",
            "updated_at": "2021-04-20T07:01:14.000000Z",
            "workshops": [
                {
                    "id": 4,
                    "start": "2021-08-21 10:00:00",
                    "end": "2021-08-21 18:00:00",
                    "event_id": 3,
                    "name": "#NoClass pure functional programming",
                    "created_at": "2021-04-20T07:01:14.000000Z",
                    "updated_at": "2021-04-20T07:01:14.000000Z"
                },
                {
                    "id": 5,
                    "start": "2021-08-21 09:00:00",
                    "end": "2021-08-21 17:00:00",
                    "event_id": 3,
                    "name": "Navigating the function jungle",
                    "created_at": "2021-04-20T07:01:14.000000Z",
                    "updated_at": "2021-04-20T07:01:14.000000Z"
                }
            ]
        }
    ]
    ```
     */

    public function getFutureEventsWithWorkshops() {
        $events = Event::leftJoin('workshops', 'events.id', '=', 'workshops.event_id')
                ->where('workshops.start', '>', now())
                ->select('events.id', 'events.name', 'events.created_at', 'events.updated_at', 
                    DB::raw('GROUP_CONCAT(JSON_OBJECT("w_id", workshops.id, "w_name", workshops.name, "start", 
                        workshops.start, "end", workshops.end, "workshops.event_id", "event_id", "w_created_at", "workshops.created_at", "w_updated_at", "workshops.updated_at")) AS workshops')
                )
                ->groupBy('events.id')
                ->get();

        return $events;


        /**
            Note**

            the below code uses post process query result in php but it output the exact output and passed the test

            $upcomingEvents = Event::select('events.id', 'events.name', 'events.created_at', 'events.updated_at')
                ->whereIn('events.id', function($query) {
                    $query->select('event_id')
                        ->from('workshops')
                        ->where('start', '>', now())
                        ->groupBy('event_id')
                        ->havingRaw('MIN(start) > ?', [now()]);
                })
                ->orderBy('events.id')
                ->get();

            $upcomingEvents = $upcomingEvents->map(function ($event) {
                $event['workshops'] = Workshop::where('event_id', $event['id'])
                    ->where('start', '>', now())
                    ->orderBy('start')
                    ->get(['id', 'start', 'end', 'event_id', 'name', 'updated_at', 'created_at'])
                    ->toArray();
                unset($event['id']);
                return $event;
            });

            return $upcomingEvents;
        **/

                




        throw new \Exception('implement in coding task 2');
    }
}
