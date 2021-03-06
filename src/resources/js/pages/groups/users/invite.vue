<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header" v-once>
                <h1 class="page-title text-center">
                    <b>Add user to "{{ group.name }}"</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <label class="form-label">User</label>
                            <selectize
                                v-model.trim="user"
                                class="form-select"
                                placeholder="Select user..."
                                :class="{
                                    'is-invalid': $page.errors.user_id,
                                }"
                                label="name"
                                :keys="['name', 'email']"
                                :options="userList"
                                :createItem="false"
                                :searchFn="searchUser"
                            >
                                <template #option="{ option }">
                                    <div>{{ option.name }}</div>
                                    <div class="text-muted small">
                                        {{ option.company }}
                                    </div>
                                </template>
                            </selectize>
                            <span
                                v-if="$page.errors.user_id"
                                class="invalid-feedback"
                            >
                                <strong>
                                    {{ $page.errors.user_id }}
                                </strong>
                            </span>
                            <p class="mt-3 text-muted small" v-once>
                                You can invite registered users email address
                                matches {{ group.domain }}
                            </p>
                            <p class="text-muted small">
                                User is not registered? Invite them to the "{{
                                    group.name
                                }}"
                                <a
                                    :href="
                                        route(
                                            'groups.user-invitations.create',
                                            group.id
                                        )
                                    "
                                    >here</a
                                >
                            </p>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('groups.users.index', group.id)"
                                class="btn btn-link"
                                v-once
                            >
                                Cancel
                            </inertia-link>
                            <button type="submit" class="btn btn-primary">
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                Invite
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo() {
        return {
            title: `Add user to ${this.group.name}`,
        };
    },
    components: {
        Layout,
    },
    props: {
        group: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            user: null,
            userList: [],
            sending: false,
        };
    },
    mounted() {
        this.loadUserList();
    },
    methods: {
        submit() {
            this.sending = true;

            this.$inertia
                .post(route('groups.users.store', this.group), {
                    user_id: this.user?.id,
                })
                .then(() => (this.sending = false));
        },
        loadUserList(query = '') {
            axios
                .get(route('groups.users.candidates', this.group), {
                    params: { q: query },
                })
                .then((result) => {
                    this.userList = result.data.data;
                });
        },
        searchUser(query, callback) {
            this.loadUserList(query);
            callback();
        },
    },
};
</script>
