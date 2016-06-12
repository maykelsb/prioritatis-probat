<?php
namespace Pprobat\Controller;

use Symfony\Component\HttpFoundation\Request;

class MeetupControllerProvider extends AbstractControllerProvider
{
    protected function enableRoutes() {
        $this->listMeetups()
            ->editMeetups()
            ->viewMeetup();
    }

    protected function listMeetups()
    {
        $this->cc->get('/', function(){
            $sql = <<<DML
SELECT id,
       title,
       local,
       happening,
       meetuptype,
       notes
  FROM meetup
DML;
            $meetups = $this->app['db']->fetchAll($sql);

            return $this->app['twig']->render('meetup/list.html.twig', [
                'meetups' => $meetups
            ]);
        })->bind('meetups_list');

        return $this;
    }

    protected function editMeetups()
    {
        $this->cc->match('/edit/{meetup}', function(Request $request, $meetup = null){

            return $this->handleForm($request, $meetup);
        })->bind('meetup_edit')
            ->value('meetup', null)
            ->convert('meetup', 'converter.meetup:convert');

        return $this;
    }

    protected function viewMeetup()
    {
        $this->cc->get('/view/{meetup}', function($meetup){
            return $this->app['twig']->render('meetup/view.html.twig', [
                'meetup' => $meetup
            ]);

        })->bind('meetup_view')
            ->convert('meetup', 'converter.meetup:convert');
    }

    /**
     * {@inheritdoc}
     */
    protected function filterData(array &$data)
    {
        filter_var_array($data, [
            'titulo' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'local' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'notes' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'report' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
        ]);
    }

    protected function prePersist(array &$data) {
        $data['happening'] = $data['happening']->format('Y-m-d H:m');
    }
}
