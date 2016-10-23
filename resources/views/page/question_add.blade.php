<div ng-controller="QuestionAddController" class="question-add container">
        <form name="question_add_form" ng-submit="Question.add()">
            <div class="input-group">
                <label>问题标题</label>
                <input type="text"
                       name="title"
                       ng-minlength="5"
                       ng-maxlength="255"
                       ng-model="Question.new_question.title"
                       required
                ></input>
            </div>
            <div class="input-group">
                <label>问题描述</label>
                    <textarea type="text"
                              name="desc"
                              ng-model="Question.new_question.desc">
                    </textarea>
            </div>
            <div class="input-group">
                <button type="submit"
                      ng-disabled="question_add_form.$invalid"
                        class="primary">提交</button>
            </div>
        </form>
    </div>
