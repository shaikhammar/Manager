import { useState } from 'react';
import { store } from '@/actions/App/Http/Controllers/AccountController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/account';
import { Account, BreadcrumbItem } from '@/types';
import { Form, Head, router, usePage } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: index().url,
    },
    {
        title: 'Create',
        href: '#',
    },
];

export default function AccountCreate() {
    const { parents, types } = usePage<{
        parents: Account[];
        types: string[];
    }>().props;

    const [type, setType] = useState<string>('');
    const [parentId, setParentId] = useState<string>('');

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create New Account" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="mx-auto flex w-full max-w-2xl flex-col gap-4 p-4">
                    <h1 className="text-2xl font-bold">Create New Account</h1>

                    <Form
                        {...store.form()}
                        options={{
                            preserveScroll: true,
                        }}
                        resetOnSuccess
                        disableWhileProcessing
                    >
                        {({ errors, processing }) => (
                            <>
                                <div className="grid w-full gap-2 p-4">
                                    <Label htmlFor="name">Name</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        type="text"
                                        required
                                        autoFocus
                                        tabIndex={1}
                                        placeholder="Enter account name"
                                    />
                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid w-full gap-2 p-4">
                                    <Label htmlFor="code">Code</Label>
                                    <Input
                                        id="code"
                                        name="code"
                                        type="text"
                                        required
                                        tabIndex={2}
                                        placeholder="Enter account code"
                                    />
                                    <InputError message={errors.code} />
                                </div>

                                <div className="grid w-full gap-2 p-4">
                                    <Label htmlFor="type">Type</Label>
                                    <Select 
                                        name="type" 
                                        disabled 
                                        value={type}
                                    >
                                        <SelectTrigger tabIndex={-1}>
                                            <SelectValue placeholder="Type will be inferred from parent" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {types.map((t) => (
                                                <SelectItem key={t} value={t}>
                                                    {t}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.type} />
                                </div>

                                <div className="grid w-full gap-2 p-4">
                                    <Label htmlFor="parent_id">Parent Account</Label>
                                    <Select
                                        name="parent_id"
                                        required
                                        onValueChange={(value) => {
                                            const parent = parents.find(
                                                (p) => p.id.toString() === value,
                                            );
                                            setParentId(value);
                                            if (parent) {
                                                setType(parent.type);
                                            }
                                        }}
                                        value={parentId}
                                    >
                                        <SelectTrigger tabIndex={4}>
                                            <SelectValue placeholder="Select parent account" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {parents.map((account) => (
                                                <SelectItem
                                                    key={account.id}
                                                    value={account.id.toString()}
                                                >
                                                    {account.code} - {account.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.parent_id} />
                                </div>

                                <div className="flex items-center gap-2 p-4">
                                    <Checkbox
                                        id="is_selectable"
                                        name="is_selectable"
                                        defaultChecked={true}
                                        tabIndex={5}
                                    />
                                    <Label htmlFor="is_selectable">
                                        Allow transactions to be posted to this account
                                    </Label>
                                    <InputError message={errors.is_selectable} />
                                </div>

                                <div className="mt-4 flex flex-col gap-4 md:flex-row p-4">
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                        tabIndex={6}
                                        className="h-auto w-full whitespace-normal md:w-1/2"
                                    >
                                        Create New Account
                                    </Button>
                                    <Button
                                        type="button"
                                        variant="destructive"
                                        disabled={processing}
                                        tabIndex={7}
                                        className="w-full md:w-1/2"
                                        onClick={() => router.visit(index())}
                                    >
                                        Cancel
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </div>
        </AppLayout>
    );
}
