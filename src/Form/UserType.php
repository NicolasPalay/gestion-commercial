<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        
        $builder->add('email');

        // Ajouter le champ des rôles uniquement pour les utilisateurs avec le rôle ROLE_DIRIGEANT
        if ($user && in_array('ROLE_DIRIGEANT', $user->getRoles())) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Editeur' => 'ROLE_COLABORATEUR',
                    'Dirigeant' => 'ROLE_DIRIGEANT',
                ],
                'multiple' => true,   // Permet la sélection multiple
                'expanded' => true,   // Affiche des cases à cocher
            ]);
        }


           
            if ($user && in_array('ROLE_DIRIGEANT', $user->getRoles())) {
                $builder
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'id',
            ]);
        }
    }
   
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => null,
        ]);
    }
}
