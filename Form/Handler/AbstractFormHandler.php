<?php

namespace AppVerk\Components\Form\Handler;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractFormHandler
{
    /** @var  FormFactory */
    protected $formFactory;

    /** @var FormInterface */
    protected $form;

    /** @var Request */
    protected $request;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @required
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @required
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(string $formTypeClass, $model, array $options = [])
    {
        $this->createForm($formTypeClass, $model, $options);

        return $this;
    }

    /**
     * @required
     * @param RequestStack $requestStack
     */
    public function setRequest(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function process()
    {
        $this->validateForm();
        $this->form->handleRequest($this->request);

        if (!$this->isValid()) {
            $this->errors = $this->getErrorsFromForm($this->form);

            return false;
        }

        $response = $this->success();

        if (!$response) {
            $this->errors = $this->getErrorsFromForm($this->form);

            return false;
        }

        return $response;
    }

    /**
     * Build inheritance form errors
     *
     * @param FormInterface $form
     * @return array
     */
    protected function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];
        /** @var FormError $error */

        if($form->getErrors()->count() === 0 && $form->isRoot() && !$form->isValid()){
            $errors["_self"] = "Empty post data";
        }

        foreach ($form->getErrors() as $error) {
            $key = $error->getOrigin()->getName();
            $errors[($form->isRoot()) ? "_self" : $key] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    unset($errors["_self"]);
                    $errors = array_merge_recursive($errors, $childErrors);
                }
            }
        }

        return $errors;
    }

    public function isValid(): bool
    {
        if (true === $this->form->isValid()) {
            return true;
        }

        return false;
    }

    /**
     * @param $formTypeClass
     * @param $model
     */
    private function createForm($formTypeClass, $model, array $options = [])
    {
        $this->form = $this->formFactory->create($formTypeClass, $model, $options);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function getErrorsAsString()
    {
        $message = '';
        foreach ($this->errors as $error) {

            if (is_string($error)) {
                $message .= $error;
                continue;
            }
            if (!is_array($error)) {
                $message .= implode(", ", $error);
            } else {
                if (count($error) === 1) {
                    if (is_string(array_values($error)[0])) {
                        $message .= ", ".array_values($error)[0];
                        continue;
                    }
                    foreach (array_values($error) as $messages) {
                        $message .= implode(", ", $messages);
                    }
                    continue;
                }
                foreach ($error as $messages) {
                    $message .= implode(", ", $messages);
                }
            }
        }

        return implode("", explode(", ", $message, 2));
    }

    public function getFormView()
    {
        $this->validateForm();

        return $this->form->createView();
    }

    protected function validateForm()
    {
        if ($this->form === null) {
            throw new \Exception("First u need call buildForm method to create form");
        }
    }

    protected function addFormError($message, array $params = [])
    {
        $message = $this->translator->trans($message, $params, 'forms');
        $this->form->addError(new FormError($message));
    }

    protected function addChildFormError($child, $message, array $params = [])
    {
        $message = $this->translator->trans($message, $params, 'forms');
        $children = explode(".", $child);

        $form = $this->form;
        foreach ($children as $child){
            $form = $form->get($child);
        }

        $form->addError(new FormError($message));
    }

    /**
     * Place for your logic
     * Database operation or some kind of API action, etc
     */
    abstract protected function success();

}
