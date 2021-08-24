<?php headerAdmin($data); ?>
    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i><?= $data['page_title']; ?></h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/dashboard"><?= $data['page_tag']; ?></a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">Dashboard</div>
            <!--MOSTRAMOS LOS DATOS QUE TRAE LA VARIABLE DE SESSION (datos del usuario)-->
            <!--?php 
              //dep($_SESSION['userData']);
              //vemos los permisos asignados a cada uno de los modulos del usuario
              //dep(getPermisos(1));
              //getPermisos(1);
              //TODOS LOS MODULOS
              //dep($_SESSION['permisos']);
              //MODULO DONDE ESTAMOS
              //dep($_SESSION['permisosMod']);
             ?>-->
          </div>
        </div>
      </div>
    </main>
<?php footerAdmin($data); ?>