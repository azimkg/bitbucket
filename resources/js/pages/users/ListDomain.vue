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
                        Проверить домен
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
                <div class="modal-body">
                    <h6>Проверка займет меньше минуты...</h6>
                    <form autocomplete="off">
                        <div class="form-group">
                            <label for="name">Domains</label>
                            <textarea
                                v-model="form.title"
                                type="text"
                                class="form-control"
                                id="name"
                                aria-describedby="nameHelp"
                                placeholder="Enter domains"
                            />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Назад
                    </button>
                    <button
                        @click="postDomain"
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
import { reactive } from "vue";
import axios from "axios";


let domains = [];

function getDomdain(item) {
    if (typeof item === "string") {
        domains.push(item);
    } else {
        domains.push(...item);
    }
}

const form = reactive({
    title: "",
});

function postDomain() {
    let regexp = /[а-яё]/i;
    if (regexp.test(form.title)) {
        return alert("Введите латинские символы!");
    }
    try {
        axios.post("/api/checked", form).then((res) => {
            domains = [];
            getDomdain(res.data);
            form.title = "";
            $("#createUser").modal("hide");
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
