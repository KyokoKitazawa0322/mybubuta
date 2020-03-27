<?php 
error_reporting(E_ALL ^ E_NOTICE);
mb_regex_encoding("UTF-8"); 

//---------------------------------------
//ログイン用
function requiredError($post) {
    $requiredErrors = "";
    $requiredCheck = array(
        'パスワード' => $post['password'],
        'メール' => $post['mail']
    );
    foreach ($requiredCheck  as $key => $value) {
        if(empty($value)) {
            $$requiredErrors = $key.'は必須入力です。';
        }
        return $requiredErrors; 
      }
}

//---------------------------------------
//新規会員登録用
function mailExists($session){
    $mailExistsErrors = "";
    $con = new Connection();
    $pdo = $con->pdo();
    $sql = "SELECT * FROM customers WHERE mail=? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $session['mail']);
    $stmt->execute();
    if($result = $stmt->fetch()){
        $mailExistsErrors = "既に使用されているメールアドレスです。";
    }
    return $mailExistsErrors; 
}

//---------------------------------------
//会員登録情報の変更用
function mailExistEx($session){
    $mailExistsErrors = "";
    $con = new Connection();
    $pdo = $con->pdo();
    $sql = "SELECT * FROM customers WHERE mail=? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $session['mail']);
    $stmt->execute();
    if($result = $stmt->fetch()){
        if($result['customer_id'] !== $session['customer_id']){
            $mailExistsErrors = "既に使用されているメールアドレスです。";
        }
    }
    return $mailExistsErrors; 
}

//---------------------------------------

function lastNameValidation($session) {
    $validateErrors = "";
    $doubleByteCheck = array(
      '氏名(姓)' => $session['name01']
    );
    foreach ($doubleByteCheck as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }
        return $validateErrors; 
      }
    }

    $lastNameErrors = lastNameValidation($session);
    if(empty($lastNameErrors)) {
    $isLastNameError = false;
    } else {
    $isLastNameError = true;
}
//---------------------------------------

function firstNameValidation($session) {
    $validateErrors = "";
    $doubleByteCheck = array(
      '氏名(名)' => $session['name02']
    );
    foreach ($doubleByteCheck as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }elseif(!preg_match('/^[ぁ-んァ-ヶー一-龠 　\r\n\t]+$/u',$value)){
            $validateErrors = $key.'は全角文字で入力して下さい。';
        }
        return $validateErrors; 
      }
    }

    $firstNameErrors = firstNameValidation($session);
    if(empty($firstNameErrors)) {
    $isFirstNameError = false;
    } else {
    $isFirstNameError = true;
 }
//---------------------------------------

function rubyLastNameValidation($session) {
    $validateErrors = "";
    $doubleByterubyCheck = array(
      '氏名(セイ)' => $session['name03']
    );
    foreach ($doubleByterubyCheck as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }elseif(!preg_match('/^[ア-ン゛゜ァ-ォャ-ョー「」、]+$/u',$value)) {
        $validateErrors = $key.'は全角カタカナで入力して下さい。';
        }
        return $validateErrors; 
      }
    }

    $rubyLastNameErrors = rubyLastNameValidation($session);
    if(empty($rubyLastNameErrors)) {
    $isRubyLastNameError = false;
    } else {
    $isRubyLastNameError = true;
 }

//---------------------------------------

function rubyFirstNameValidation($session) {
    $validateErrors = "";
    $doubleByterubyCheck = array(
      '氏名(メイ)' => $session['name04']
    );
    foreach ($doubleByterubyCheck as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }elseif(!preg_match('/^[ア-ン゛゜ァ-ォャ-ョー「」、]+$/u',$value)) {
        $validateErrors = $key.'は全角カタカナで入力して下さい。';
        }
        return $validateErrors; 
      }
    }
    $rubyFirstNameErrors = rubyFirstNameValidation($session);
    if(empty($rubyFirstNameErrors)) {
    $isRubyFisrNameError = false;
    } else {
    $isRubyFirstNameError = true;
}
//---------------------------------------

function mailValidation($session) {
    $validateErrors = "";
    $mailFormatCheck = array(
      'メールアドレス' => $session['mail']
    );
    foreach ($mailFormatCheck  as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }elseif(!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',$value)) {
        $validateErrors = $key.'を正しく入力して下さい。';
        }
        return $validateErrors; 
      }
    }

    $mailErrors = mailValidation($session);
    if(empty($mailErrors)) {
    $isMailError = false;
    } else {
    $isMailError = true;
}

//---------------------------------------
function telValidation($session) {
    $validateErrors = "";
    $telFormatCheck = array(
      '電話番号' => $session['tel']
    );
    foreach ($telFormatCheck  as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }elseif(!preg_match('/^(0{1}\d{9,10})$/',$value)) {
        $validateErrors = $key.'は半角数字で市外局番から正しく入力してください。';
        }
        return $validateErrors; 
      }
    }

    $telErrors = telValidation($session);
    if(empty($telErrors)) {
    $isTelError = false;
    } else {
    $isTelError = true;
}
//---------------------------------------
function zipcodeFirstValidation($session) {
    $validateErrors = "";
    $zipcodeFirstFormatCheck = array(
      '郵便番号(３ケタ)' => $session['add01']
    );
    foreach ($zipcodeFirstFormatCheck  as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }elseif(!preg_match('/^\d{3}$/',$value)) {
        $validateErrors = $key.'を正しく入力して下さい。';
        }
        return $validateErrors; 
      }
    }

    $zipcodeFirstErrors = zipcodeFirstValidation($session);
    if(empty($zipcodeFirstErrors)) {
    $isZipcodeFirstError = false;
    } else {
    $isZipcodeFirstError = true;
}

//---------------------------------------
function zipcodelastValidation($session) {
    $validateErrors = "";
    //郵便番号フォーマットチェック対象指定
    $zipcodeLastFormatCheck = array(
      '郵便番号(４ケタ)' => $session['add02']
    );
    foreach ($zipcodeLastFormatCheck  as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }elseif(!preg_match('/^\d{4}$/',$value)) {
        $validateErrors = $key.'を正しく入力して下さい。';
        }
        return $validateErrors; 
      }
    }

    $zipcodeLastErrors = zipcodelastValidation($session);
    if(empty($zipcodeLastErrors)) {
    $isZipcodeLastError = false;
    } else {
    $isZipcodeLastError = true;
}

//---------------------------------------
function add03Validation($session) {
    $validateErrors = "";
    $add03FormatCheck = array(
        '都道府県' => $session['add03']
    );
    foreach ($add03FormatCheck  as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }
        return $validateErrors; 
      }
    }

    $add03Errors = add03Validation($session);
    if(empty($add03Errors)) {
    $isAdd03Error = false;
    } else {
    $isAdd03Error = true;
}

//---------------------------------------
function add04Validation($session) {
    $validateErrors = "";
    $add04FormatCheck = array(
        '市区町村' => $session['add04']
    );
    foreach ($add04FormatCheck  as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }
        return $validateErrors; 
      }
    }

    $add04Errors = add04Validation($session);
    if(empty($add04Errors)) {
    $isAdd04Error = false;
    } else {
    $isAdd04Error = true;
}

//---------------------------------------
function add05Validation($session) {
    $validateErrors = "";
    $add05FormatCheck = array(
        '番地' => $session['add05']
    );
    foreach ($add05FormatCheck  as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }
        return $validateErrors; 
      }
    }

    $add05Errors = add05Validation($session);
    if(empty($add05Errors)) {
    $isAdd05Error = false;
    } else {
    $isAdd05Error = true;
}

//---------------------------------------
  //パスワードチェック
function passValidation($session) {
    $validateErrors = "";
    $passFormatCheck = array(
        'パスワード' => $session['password']
    );
    foreach ($passFormatCheck  as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }elseif(!preg_match("/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,20}$/", $value)){
            $validateErrors = $key.'は英字・数字を含め8～20文字で入力してください。';
        }
        return $validateErrors; 
      }
}
//--------------------------------------- 

function passConfValidation($session) {
    $validateErrors = "";
    $passConfFormatCheck = array(
        'パスワード(再確認)' => $session['confirm']
    );
    foreach ($passConfFormatCheck  as $key => $value) {
        if(empty($value)) {
            $validateErrors = $key.'は必須入力です。';
        }elseif($value !== $session['password']){
            $validateErrors = $key.'が一致しません。';
        }
        return $validateErrors; 
      }

}
    
?>
