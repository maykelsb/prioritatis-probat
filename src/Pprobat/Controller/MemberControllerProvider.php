<?php
namespace Pprobat\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

use Pprobat\Form\Type\MemberType;

class MemberControllerProvider implements ControllerProviderInterface
{
    /**
     * @var Silex\Application
     */
    protected $app;
    /**
     * @var Silex\ControllerCollection
     */
    protected $cc;

    /**
     * Stablish routes for this controller.
     *
     * @param Silex\Application $app
     * @return Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        $this->app = $app;
        $this->cc = $app['controllers_factory'];

        $this->listMembers()
            ->editMember()
            ->viewMember();

        return $this->cc;
    }

    protected function listMembers()
    {
        $this->cc->get('/', function(){
            $members = $this->app['db']->fetchAll('SELECT name, affiliation FROM user');

            return $this->app['twig']->render('members/list.html.twig', [
                'members' => $members
            ]);
        })->bind('get_members');

        return $this;
    }

    protected function editMember()
    {
        $this->cc->match('/new/{id}', function(Request $request, $id = null){
            $form = $this->app['form.factory']
                ->createBuilder(MemberType::class)
                ->getForm();

            $form->handleRequest($request);

            if (!$form->isValid() || !$form->isSubmitted()) {
                return $this->app['twig']->render('members/form.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $data = $form->getData();
            $this->filterData($data);
            $data['affiliation'] = $data['affiliation']->format('Y-m-d');

            if (is_null($id)) {
                $this->app['db']->insert('user', $data);
                $id = $this->app['db']->lastInsertId();
            } else {
                $this->app['db']->update('user', $data, ['id' => $id]);
            }

            return $this->app->redirect("/members/{$id}");
        })->bind('edit_member')
            ->value('id', null);

        return $this;
    }

    protected function viewMember()
    {
        $this->cc->get('/view/{member}', function($member){
            return $this->app['twig']->render('members/view.html.twig', [
                'member' => $member
            ]);

        })->bind('view_member')
        ->convert('member', 'converter.user:convert');

        return $this;
    }


    protected function filterData(&$data)
    {
        filter_var_array($data, [
            'name' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'about' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'username' => [FILTER_SANITIZE_MAGIC_QUOTES],
            'password' => [FILTER_SANITIZE_MAGIC_QUOTES],
        ]);
    }
}
