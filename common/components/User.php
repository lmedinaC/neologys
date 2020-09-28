<?php
namespace common\components;
//use common\models\masters\Usercentros;
use common\models\Useraudit;
use common\helpers\h;
use common\models\Profile;
use yii\helpers\Html;
use common\models\masters\Transacciones;
use yii;
use yii\web\User as UserOriginal;
use Carbon\Carbon;

class User extends UserOriginal {
    
    
    //public $class_profile='common\models\Profile';
    private $persona; //objeto persona 
    const LOGIN_DURATION=1800;//30 MINUTOS
    public function init(){
        
        
        
        
         parent::init();
        // $this->authTimeout=10;
         //$this->setDuration();
         //var_dump($this->authTimeout);
        // var_dump($this->authTimeout);
        //  $this->enableAutoLogin=false;
         //$this->enableSession =true;
           // Yii::info(" INICIALIZANDO CON ID  =  ".$this->id, __METHOD__);          
           
          
          // $this->absoluteAuthTimeout=20;
    }
    

   public function attributeLabels()
    {
        return [
            'id' => Yii::t('base.names', 'Id'),
            'username' => Yii::t('base.names', 'Nombre Usuario'),
            'status' => Yii::t('base.names', 'Activo'),
            'created_at' => Yii::t('base.names', 'Creado'),
            'updated_at' => Yii::t('base.names', 'Modificado'),
            
        ];
    }  
    
    /*
     * 0verrido este metodo para agregar 
     * el log de auditoria de usuario
     */
    public function afterLogin($identity, $cookieBased, $duration)
 {
     parent::afterLogin($identity, $cookieBased, $duration);
       $this->setLog();
       yii::$app->getResponse()->redirect(Yii::$app->request->referrer);
 }
    
 /*Registra la aditoria de los accwsos de suuario 
  * en la tabla audituser
  */
   public function setLog(){
       $model= Useraudit::instance();
       $model->setAttributes($this->attrMin());$model->save();
   }
   
   
   /*rpivado
    * 
    */
   private function attrMin(){
       return ['user_id'=>$this->id,
               'when'=>date('Y-m-d H:i:s'),
               'ip'=>yii::$app->request->getUserIP(),
               'action'=> Useraudit::ACTION_LOGIN,
               ];       
   }
   
   
   public function loginWithoutRedirect(IdentityInterface $identity, $duration = 0){
     
        if ($this->beforeLogin($identity, false, $duration)) {
            $this->switchIdentity($identity, $duration);
            $id = $identity->getId();
            $ip = Yii::$app->getRequest()->getUserIP();
            if ($this->enableSession) {
                $log = "User '$id' logged in from $ip with duration $duration.";
            } else {
                $log = "User '$id' logged in from $ip. Session not enabled.";
            }

            $this->regenerateCsrfToken();

            Yii::info($log, __METHOD__);
           // $this->afterLogin($identity, false, $duration);
        }

        return !$this->getIsGuest();
    
   }
   
   /*
 * Ultima vez que logueo
 * 
 */
     public function lastLogin($carbon=false){
         $fecha=Useraudit::lastLogin($this->id); 
        if($carbon){
            return Carbon::createFromFormat(\common\helpers\timeHelper::formatMysqlDateTime(), $fecha);
        }else{
          return  $fecha;
        }
         
     }       

    public function lastLoginForHumans(){
         return \Carbon\Carbon::createFromTimeStamp(
                 strtotime($this->lastLogin()))->diffForHumans();
     }   
   
     
   public function getProfile(){
      // var_dump($this->id);
       
       Profile::firstOrCreateStatic(['user_id'=>$this->id]);
       //echo "sale";die();
       return Profile::find()->where(['user_id'=>$this->id])->one();
       
   }
   
  /* public function putUrlDefault($url=null){
       if(is_null($url))
         $url=\yii\helpers\Url::current();
      return  $this->profile()->putUrl($url);
   }
   */
   public function getUrlDefault(){
       //return $this->profile->url;
      $reg= \common\models\Userfavoritos::find()->where(['user_id'=>h::userId(),'ishome'=>'1'])->one();
      return (!is_null($reg))?$reg->url:null;
   }
   
   public function resolveUrlAfterLogin(){
    yii::error( 'resolviendo afterlogin',__FUNCTION__);
       $url=$this->getUrlDefault();//verifica sus favoritos 
       //yii::error( 'Url favorito',__FUNCTION__);
       //yii::error( $url,__FUNCTION__);
       
       if(!is_null($url)){
           //yii::error( 'encontro favorito',__FUNCTION__);
          // yii::error( $url,__FUNCTION__);
          return $url;  
       }else{
           yii::error('por q aui pasa');
           //yii::error( 'No encontro favorito',__FUNCTION__);
           $persona=$this->profile->persona;
          //yii::error( $persona,__FUNCTION__);
           if(is_null($persona))
           return \yii\helpers\Url::home();//Si no al home 
           //yii::error( $persona,__FUNCTION__);
           $codgrupo=$persona->codgrupo;
           //YII::ERROR($codgrupo,__FUNCTION__);
           $url=$this->getUrlFromRoutes($codgrupo);
           //YII::ERROR($url,__FUNCTION__);
           if($url)$this->putUrlFavorite ($url);
           return ($url)?$url:\yii\helpers\Url::home();
       }
   }
   
   
   public function putUrlFavorite($url){
      return \common\models\Userfavoritos::firstOrCreateStatic([
           'user_id'=>$this->identity->id,
           'url'=>$url,
           'ishome'=>'1'],null,['user_id'=>$this->identity->id,
           'url'=>$url]
       );
               
   }
   
   
   /*
    * Esta funcion lee el valor de 
    * la ruta  registrada en 
    * la tabla auth_items , leele valor del
    * campo 
    * grupopersona  establecida 
    */
   private function getUrlFromRoutes($grupo){
      return Transacciones::find()->select(['name'])->where(['grupopersonas'=>$grupo])->scalar();
   }
   
   
   public function hasProfile(){
       return is_null($this->getProfile())?false:true;
   } 
   public function getSince(){
      return date('d/m/Y H:i:s',$this->identity->created_at);
      //return \Carbon\Carbon::createFromTimeStamp(
                // strtotime($this->lastLogin()))->diffForHumans(); 
       //Profile::firstOrCreateStatic(['user_id'=>$this->id]);
      // return Profile::find()->where(['user_id'=>$this->id])->one();
       
   }  
 
   public  function isActive(){
    return $this->identity->status == '10';
}
   
private function setDuration(){
    $duracion=$this->getProfile()->duration;            
    $this->authTimeout=(is_null($duracion))?self::LOGIN_DURATION
            :$duracion+0;
     $duracion=$this->getProfile()->durationabsolute;    
    $this->absoluteAuthTimeout=(is_null($duracion))?self::LOGIN_DURATION
            :$duracion+0;
}



public function getCenters(){
    
}

public function isTheFirstLogin(){
    return (is_null($this->lastLogin()))?true:false;
}

public function getFirstFacultad(){
   $registro= \frontend\modules\sta\models\UserFacultades::find()->where(['user_id'=>$this->id,'activa'=>'1'])->one();
  // var_dump(\frontend\modules\sta\models\UserFacultades::find()->where(['user_id'=>'1','activa'=>'1'])->createCommand()->getRawSql());die();
   if(!is_null($registro))
   return $registro->codfac;
   return null;
}

public function getFacultades(){
 return  \frontend\modules\sta\models\UserFacultades::find()->select(['codfac'])->where(['user_id'=>$this->id,'activa'=>'1'])->asArray()->column();   
}

public function isMultiFacultad(){
    return (\frontend\modules\sta\models\UserFacultades::find()->
            where(['user_id'=>$this->id,'activa'=>'1'])->
            count()>1)?true:false;   
}




}