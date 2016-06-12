<?php
namespace Pprobat\Controller;

use Symfony\Component\HttpFoundation\Request;

use Pprobat\Form\Type\MemberType;

class MemberControllerProvider extends AbstractControllerProvider
{
    protected function enableRoutes()
    {
        $this->listMembers()
            ->editMember()
            ->viewMember();
    }

    protected function listMembers()
    {
        $this->cc->get('/', function(){
            $members = $this->app['db']->fetchAll('SELECT id, username, name, affiliation FROM member');

            return $this->app['twig']->render('member/list.html.twig', [
                'members' => $members
            ]);
        })->bind('members_list');

        return $this;
    }

    protected function editMember()
    {
        $this->cc->match('/edit/{member}', function(Request $request, $member = null){

            return $this->handleForm($request, $member);
        })->bind('member_edit')
            ->value('member', null)
            ->convert('member', 'converter.member:convert');

        return $this;
    }

    protected function viewMember()
    {
        $this->cc->get('/view/{member}', function($member){
            return $this->app['twig']->render('member/view.html.twig', [
                'member' => $member
            ]);

        })->bind('member_view')
        ->convert('member', 'converter.member:convert');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterData(array &$data)
    {
        filter_var_array($data, [
            'name' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'about' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'username' => [FILTER_SANITIZE_MAGIC_QUOTES],
            'password' => [FILTER_SANITIZE_MAGIC_QUOTES],
        ]);
    }

    protected function prePersist(array &$data) {
        $data['affiliation'] = $data['affiliation']->format('Y-m-d');

        if (isset($data['creation'])) {
            $data['creation'] = $data['creation']->format('Y-m-d');
        }
    }
}
