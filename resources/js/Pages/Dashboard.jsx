import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import {  useEffect } from 'react';
import axios from 'axios';

export default function Dashboard({ auth }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        description: '',
        price: '',
        image: '',
    });

    useEffect(() => {
        fetchMenus();
    }, []);

    const fetchMenus = async () => {
        try {
            const response = await axios.get('/api/menus');
            setMenus(response.data);
        } catch (error) {
            console.error(error);
        }
    };

    const deleteMenu = async (id) => {
        try {
            await axios.delete(`/api/menus/${id}`);
            fetchMenus();
        } catch (error) {
            console.error(error);
        }
    };

    const addMenu = async () => {
        e.preventDefault();

        post(route('api/menus'));
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <button className="mb-4 text-white btn btn-success" onClick={()=>document.getElementById('create-menu-modal').showModal()}>Add Menu</button>
                            <dialog id="create-menu-modal" className="modal">
                            <div className="modal-box">
                                <h3 className="text-lg font-bold">Hello!</h3>
                                <p className="py-4">Press ESC key or click the button below to close</p>
                                <div className="modal-action">
                                <form method="dialog">
                                    <div>
                                        <InputLabel htmlFor="name" value="Name" />

                                        <TextInput
                                            id="name"
                                            type="name"
                                            name="name"
                                            value={data.name}
                                            className="block w-full mt-1"
                                            isFocused={true}
                                            onChange={(e) => setData('name', e.target.value)}
                                        />

                                        <InputError message={errors.email} className="mt-2" />
                                    </div>
                                    <div>
                                        <InputLabel htmlFor="description" value="Description" />

                                        <TextInput
                                            id="description"
                                            type="description"
                                            name="description"
                                            value={data.name}
                                            className="block w-full mt-1"
                                            onChange={(e) => setData('description', e.target.value)}
                                        />

                                        <InputError message={errors.description} className="mt-2" />
                                    </div>
                                    <div>
                                        <InputLabel htmlFor="price" value="Price" />

                                        <TextInput
                                            id="price"
                                            type="price"
                                            name="price"
                                            value={data.price}
                                            className="block w-full mt-1"
                                            onChange={(e) => setData('price', e.target.value)}
                                        />

                                        <InputError message={errors.description} className="mt-2" />
                                    </div>
                                    <button className="text-white btn btn-success" onClick={addMenu}>Create</button>
                                    <button className="btn">Close</button>
                                </form>
                                </div>
                            </div>
                            </dialog>
                            <h3 className="mb-4 text-lg font-semibold">Menus</h3>
                            
                            <ul>
                                {menus.map((menu) => (
                                    <li key={menu.id} className="flex items-center justify-between mb-2">
                                        <span>{menu.name}</span>
                                        <button
                                            className="text-red-500"
                                            onClick={() => deleteMenu(menu.id)}
                                        >
                                            Delete
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
