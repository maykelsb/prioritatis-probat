<?php
/**
 * This file is part of Prioritatis Probat project.
 *
 * This is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3, or (at your option)
 * any later version.
 *
 * @link https://github.com/maykelsb/prioritatis-probat
 */
namespace Pprobat\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\DBAL\Connection;

/**
 * Form to manage session data.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class SessionType extends AbstractType
{
    /**
     * @var Doctrine\DBAL\Connection
     */
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('game', ChoiceType::class, [
            'label' => 'Jogo:',
            'choices' => $this->loadGameChoices()
        ])
        ->add('member', ChoiceType::class, [
            'label' => 'Jogadores:',
            'multiple' => true,
            'choices' => $this->loadMemberChoices($options['data']['meetup'])
        ])
        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn-primary']
        ]);
    }

    public function getName()
    {
        return self::class;
    }

    protected function loadGameChoices()
    {
        $sql = <<<DML
SELECT g.id,
       g.name,
       m.name AS designer
  FROM game g
    INNER JOIN member_game mg ON (g.id = mg.game)
    INNER JOIN member m ON (mg.member = m.id)
  ORDER BY m.name ASC
DML;
        $games = [];
        foreach($this->db->fetchAll($sql) as $game) {
            $games[$game['designer']][$game['name']] = $game['id'];
        }

        return $games;
    }

    protected function loadMemberChoices()
    {
        $sql = <<<DML
SELECT m.id,
       m.name
  FROM member m
  ORDER BY m.name desc
DML;
        $members = [];

        foreach ($this->db->fetchAll($sql) as $member) {
            $members[htmlspecialchars($member['name'])] = $member['id'];
        }

        return $members;
    }
}
