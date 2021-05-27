<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ContactUsController extends Controller
{

    public function getContactList()
    {
        try {
                
            $result = ContactUs::paginate(15);
            if (!$result) {
                return $this->respondWithError('No record found!');
            }
            return $this->respondWithSuccess($result);
        }
        catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }



    public function remove($id)
    {
        try {
            $contactUs = ContactUs::find($id);
            if (!$contactUs) {
                return $this->respondWithError('Record not found!');
            }

            $contactUs->delete();

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    
    public function getById($id)
    {
        $result = ContactUs::find($id);
        return $this->respondWithSuccess($result);
    }

	public function contactForm(Request $request){

        $contactUs = new ContactUs;
        
        try{
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'phone'=> 'required',
                'company' => 'required',
                'message' => 'required'
              
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $contactUs = new ContactUs();
                $contactUs->name = $request->input('name');
                $contactUs->email = $request->input('email');
                $contactUs->phone = $request->input('phone');
                $contactUs->company = $request->input('company');
                $contactUs->message = $request->input('message');
                //$contactUs->save();

                            
                $user = 'userEmail';
                $emailType = 'Comment';
                $emailView = 'emails.contact_us';
                $emailContent = 'Lorem ipsum';
                $emailContent['Content'] = 'Place Email Body Here';
                $emailSubject = 'Place Subject Line Here ';
                $emailContent['Header'] = $emailSubject;
                $emailContent['buttonURL'] = '/';
                $emailContent['buttonTitle'] = 'Button Text';
                
                Mail::send($emailView, ['user' => $user, 'emailContent' => $emailContent], function ($m) use ($user, $emailSubject) {
                                $m->from('support@email.com', 'emailName');
                                $m->to($user->first()->email, $user->first()->fname . ' ' . $user->first()->lname)->subject($emailSubject);
                            });
                
        

                return $this->respondWithSuccess($contactUs);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }


}
