    {
        return view('admin.environment-type.edit', [
            'environmentType' => $this->environmentType->getById($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:environment_types,name,' . $id]
        ]);

        try {
            $this->environmentType->update($id, $request->all());
            return redirect()->route('admin.environment-type.index')->with('success', 'Jenis lingkungan berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->environmentType->destroy($id);
            return response()->json([
                'status' => true,
                'message' => 'Jenis lingkungan berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
