import bsCustomFileInput from 'bs-custom-file-input'
import MapsService from "../Services/MapsService";

const Private = Symbol('private');

class TripController {
    init(){
        bsCustomFileInput.init();
        this[Private]().registerEvents();
    }

    [Private]() {
        let Private = {};

        Private.registerEvents = () => {
            $('.add-trip').on('click', e => {
                e.preventDefault();
                e.stopPropagation();

                $('span[id$="error"]').each((index, item) => {
                    item.textContent = '';
                });

                let form = new FormData();
                let files = $('#add-trip-form [name="gpx"]').prop('files');
                let type = $('#add-trip-form [name="type"]').val();
                let name = $('#add-trip-form [name="name"]').val();

                form.set('name', name);
                form.set('type', type > 0 ? type : '');
                form.set('gpx', files[0], 'test');

                $.ajax({
                    type: 'POST',
                    url: '/api/add-trip',
                    data: form,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        let id = response.id;

                        $('.table tbody').append(`
                            <tr>
                                <td>${id}</td>
                                <td>${name}</td>
                                <td>${window.tripTypes[type]}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-secondary">View trip</button>
                                        <button type="button" class="btn btn-secondary remove-trip" data-id="${id}">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        `);

                        $('.trip-modal').modal('hide');
                    },
                    error: function (error) {
                        let errors = error.responseJSON.errors;

                        for (let prop in errors) {
                            $(`#${prop}-error`).text(errors[prop]);
                        }
                    }
                });
            });

            $(document).on('click touchend', '.remove-trip', e => {
                let tripId = e.currentTarget.dataset.id;
                let tr = $(e.currentTarget).parents('tr');

                $.ajax({
                    type: 'DELETE',
                    url: `/api/delete-trip/${tripId}`,
                    dataType: 'json',
                    success: response => {
                        tr.remove();
                    },
                    error: (xhr,status,error) => {

                    }
                });
            });

            $('.trip-modal').off().on('hide.bs.modal', function () {
                $('#add-trip-form').trigger('reset');

                $('span[id$="error"]').each((index, item) => {
                    item.textContent = '';
                });
            });

            Private.showRouteModal();
        }

        Private.showRouteModal = () => {
            let tripId = null;

            $(document).on('click touchend', '.view-route', e => {
                tripId = e.currentTarget.dataset.id;

                $('#routeViewModal').modal('show');
            });

            $('.route-modal').off().on('shown.bs.modal', function () {

                MapsService().renderMapWithPath(tripId);
            });
        };

        return Private;
    }
};

export default TripController;