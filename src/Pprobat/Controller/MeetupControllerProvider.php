<?php
namespace Pprobat\Controller;

use Pprobat\Form\Type\MeetupType;
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

            return $this->app['twig']->render('meetups/list.html.twig', [
                'meetups' => $meetups
            ]);
        })->bind('meetups_list');

        return $this;
    }

    protected function editMeetups()
    {
        $this->cc->match('/edit/{meetup}', function(Request $request, $meetup = null){
            $form = $this->app['form.factory']
                ->createBuilder(MeetupType::class, $meetup)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && !$form->isValid()) {
                $this->app['session']->getFlashBag()->add(
                    'danger', 'Não foi possível processar sua requisição.'
                );
            }

            if (!$form->isValid() || !$form->isSubmitted()) {
                return $this->app['twig']->render('meetups/form.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $data = $form->getData();
            $this->filterData($data);
            $data['happening'] = $data['happening']->format('Y-m-d H:m');

            if (!isset($meetup['id'])) {
                $this->app['db']->insert('meetup', $data);
                $meetup['id'] = $this->app['db']->lastInsertId();
            } else {
                $this->app['db']->update('meetup', $data, ['id' => $meetup['id']]);
            }

            $this->app['session']->getFlashBag()->add(
                'success', 'Requisição processada com sucesso.'
            );

            return $this->app->redirect(
                $this->app['url_generator']->generate(
                    'meetup_view',
                    ['meetup' => $meetup['id']]
                )
            );
        })->bind('meetup_edit')
            ->value('meetup', null)
            ->convert('meetup', 'converter.meetup:convert');

        return $this;
    }

    protected function viewMeetup()
    {
        $this->cc->get('/view/{meetup}', function($meetup){
            return $this->app['twig']->render('meetups/view.html.twig', [
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
}
