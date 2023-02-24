<template>
    <div class="content">
        <div class="container-fluid">
            <button
                type="button"
                class="mb-2 btn btn-primary"
                data-toggle="modal"
                data-target="#createUser"
                style="margin-top: 20px; margin-left: 20px"
            >
                Проверить домен
            </button>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Available</th>
                            <th>Expiration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="domain in domains"
                            v-show="typeof domain == 'string'"
                        >
                            <div class="text_red">
                                {{ domain }}
                            </div>
                        </tr>
                        <tr
                            v-for="domain in domains"
                            :key="domain"
                            v-show="typeof domain === 'object'"
                        >
                            <td>{{ domain.domain }}</td>
                            <td>
                                {{
                                    domain.isAvailable
                                        ? "Свободен"
                                        : "Не свободен"
                                }}
                            </td>
                            <td>{{ domain.expirationDate }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="createUser"
        data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Проверка домена
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-show="isVisible === true">
                    <h6>Проверка займет меньше минуты...</h6>
                </div>
                <div class="modal-body" v-show="isVisible === false">
                    <form autocomplete="off">
                        <div class="form-group">
                            <label for="name">Домен</label>
                            <textarea
                                v-model="form.title"
                                type="text"
                                class="form-control"
                                id="name"
                                aria-describedby="nameHelp"
                                placeholder="Введите домен"
                            />
                        </div>
                    </form>
                </div>
                <div class="modal-footer" v-show="isVisible === false">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Назад
                    </button>
                    <button
                        @click="checkDomain"
                        type="button"
                        class="btn btn-primary"
                    >
                        Проверить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { shallowReactive, ref } from "vue";
import axios from "axios";

let domains = ref([]);
let isVisible = false;

const form = shallowReactive({
    title: "",
});

function isVisibled() {
    setTimeout(() => {
        isVisible = false;
    });
}

// Отправка каждого элемента введенных доменов
// Один домен - один запрос
function checkDomain() {
    let regeByRussian = /[а-яё]/i;
    if (regeByRussian.test(form.title)) {
        return alert("Введите латинские символы!");
    }
    try {
        // Приводим данные в нужный нам формат
        form.title
            .toLowerCase()
            .split(",")
            ?.forEach((title) => {
                axios.post("/api/checked", title).then((res) => {
                    let arr = res.data?.map((elem) => {
                        domains.value.push(elem);
                    });
                    $("#createUser").modal("hide");
                });
                form.title = "";
                isVisible = true;
                isVisibled();
            });
    } catch (error) {
        console.log(error);
    }
}
</script>

<style scoped>
.text_red {
    text-align: center;
    color: red;
    font-size: 18px;
}
</style>
