<?php $this->view("painel/include/header"); ?>

        <!-- Header -->
        <div class="header bg-gradient-primary pb-6 pt-3"></div>

        <!-- Page content -->
        <div class="container-fluid mt--6">
            <div class="row">
                <div class="col-xl-12 order-xl-1">
                    <div class="card">

                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">Alterar Usuário</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="<?= BASE_URL; ?>usuarios" class="btn btn-sm btn-primary">Ver Todos</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <form id="formAlterarUsuario" data-id="<?= $user->id_usuario; ?>">
                                <h6 class="heading-small text-muted mb-4">Campos com (*) são obrigatórios</h6>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Nome *</label>
                                            <input type="text" name="nome" value="<?= $user->nome; ?>" class="form-control" required placeholder="Nome Completo" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">E-mail *</label>
                                            <input type="email" name="email" value="<?= $user->email; ?>" class="form-control" required placeholder="nome@exemplo.com">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Senha</label>
                                            <input type="password" class="form-control" name="senha" placeholder="*****" />
                                        </div>
                                    </div>


                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Repete a senha</label>
                                            <input type="password" class="form-control" name="repeteSenha" placeholder="*****" />
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-black">
                                            Alterar
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php $this->view("painel/include/footer"); ?>
        </div>
    </div>
    <!-- Argon Scripts -->

</body>
</html>

