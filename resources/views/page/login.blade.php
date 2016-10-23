<div ng-controller="LoginController" class="login container">
        <div class="card">
            <h1>登陆</h1>
            <form name="login_form" ng-submit="User.login()">
                <div class="input-group">
                    <label>用户名</label>
                    <input type="text"
                           name="username"
                           ng-model="User.login_data.username"
                           required
                    ></input>
                </div>
                <div class="input-group">
                    <label>密码</label>
                    <input type="password"
                           name="password"
                           ng-model="User.login_data.password"
                           required
                    ></input>
                </div>
                <div ng-if="User.login_failed" class="input-error-set">
                    用户名或密码有误
                </div>
                <div class="input-group">
                    <button type="submit"
                            ng-disabled="login_form.username.$error.required||login_form.password.$error.required"
                    >登陆</button>
                </div>
            </form>
        </div>
    </div>
