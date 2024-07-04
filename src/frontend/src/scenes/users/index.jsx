import DeleteOutlinedIcon from "@mui/icons-material/DeleteOutlined";
import EditOutlinedIcon from "@mui/icons-material/EditOutlined";
import { Box, useTheme } from "@mui/material";
import { DataGrid, GridActionsCellItem, GridRowModes } from "@mui/x-data-grid";
import { createSelector } from "@reduxjs/toolkit";
import * as React from "react";
import { useCallback, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useParams } from "react-router-dom";
import SaveCancelButtons from "../../components/Buttons/SaveCancelButtons";
import Header from "../../components/Header/Header";
import HeaderButtons from "../../components/Header/HeaderButtons";
import { deleteUsersSuccess } from '../../redux/slices/UserSlice';
import { tokens } from "../../theme";
import useHelpers from "../../utils/helpers";

// EditToolbar component
const EditToolbar = React.memo(function EditToolbar({
  setRows,
  setRowModesModel,
  rows,
  arrIds,
}) {
  const dispatch = useDispatch();

  const handleClick = useCallback(() => {
    const maxId = rows.reduce((max, row) => (row.id > max ? row.id : max), 0);
    const id = maxId === 0 ? 0 : maxId + 1;
    setRows((oldRows) => [
      ...oldRows,
      {
        id,
        first_name: "",
        last_name: "",
        email: "",
        role: "",
        created_at: new Date(),
        last_login: new Date(),
        status: false,
        isNew: true,
      },
    ]);
    setRowModesModel((oldModel) => ({
      ...oldModel,
      [id]: { mode: GridRowModes.Edit, fieldToFocus: "user_id" },
    }));
  }, [rows, setRows, setRowModesModel]);

  const handleDeleteAll = useCallback(() => {
    dispatch(deleteUsersSuccess(arrIds));
  }, [dispatch, arrIds]);

  return (
    <Box>
      <Header title="Пользователи" subtitle="Управление пользователями" />
      <Box
        sx={{
          display: "flex",
          flexDirection: "row",
          alignItems: "center",
          justifyContent: "space-between",
        }}
      >
        <HeaderButtons
          addInscription="Добавить пользователя"
          deleteInscription="Удалить пользователя"
          handleClick={handleClick}
          handleDeleteAll={handleDeleteAll}
        />
      </Box>
    </Box>
  );
});

const usersSelector = createSelector(
  (state) => state.user,
  (user) => user
);

const User = () => {
  const { id } = useParams();
  const { users } = useSelector(usersSelector);
  const [arrIds, setArrIds] = useState([]);
  const [rowModesModel, setRowModesModel] = useState({});
  const [rows, setRows] = useState(users);
  const {
    editClick,
    deleteClick,
    saveClick,
    rowEditStop,
    cancelClick,
    processRowUpdate,
    rowModesModelChange,
  } = useHelpers(rowModesModel, setRowModesModel, rows, setRows);
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  React.useEffect(() => {
    setRows(users);
  }, [users]);

  const columns = React.useMemo(
    () => [
      {
        field: "first_name",
        headerName: "Имя",
        type: "string",
        flex: 1,
        editable: true,
      },
      {
        field: "last_name",
        headerName: "Фамилия",
        type: "string",
        flex: 1,
        editable: true,
      },
      {
        field: "email",
        headerName: "Электронная почта",
        type: "string",
        flex: 1,
        editable: true,
      },
      {
        field: "role",
        headerName: "Роль",
        type: "singleSelect",
        flex: 1,
        editable: true,
        valueOptions: ["администратор", "читатель", "редактор"],
      },
      {
        field: "status",
        headerName: "Статус",
        type: "boolean",
        flex: 1,
        editable: true,
      },
      {
        field: "actions",
        type: "actions",
        headerName: "Действия",
        width: 100,
        cellClassName: "actions",
        getActions: ({ id }) => {
          const isInEditMode = rowModesModel[id]?.mode === GridRowModes.Edit;

          if (isInEditMode) {
            return [
              <SaveCancelButtons
                id={id}
                saveClick={saveClick}
                cancelClick={cancelClick}
              />,
            ];
          }

          return [
            <GridActionsCellItem
              icon={<EditOutlinedIcon />}
              label="Edit"
              className="textPrimary"
              onClick={() => editClick(id)}
              color="inherit"
            />,
            <GridActionsCellItem
              icon={<DeleteOutlinedIcon />}
              label="Delete"
              onClick={() => deleteClick(id)}
              color="inherit"
            />,
          ];
        },
      },
    ],
    [rowModesModel, editClick, deleteClick, saveClick, cancelClick]
  );

  return (
    <Box
      m="15px"
      sx={{
        "& .MuiDataGrid-root": {
          border: colors.goldAccent[300],
        },
        "& .actions": {
          color: "text.secondary",
        },
        "& .textPrimary": {
          color: "text.primary",
        },
        "& .MuiCheckbox-root": {
          color: `${colors.goldAccent[300]} !important`,
        },
      }}
    >
      <DataGrid
        checkboxSelection
        rows={rows}
        columns={columns}
        editMode="row"
        rowModesModel={rowModesModel}
        onRowModesModelChange={rowModesModelChange}
        onRowEditStop={rowEditStop}
        processRowUpdate={processRowUpdate}
        slots={{
          toolbar: EditToolbar,
        }}
        onRowSelectionModelChange={(ids) => {
          setArrIds(ids);
        }}
        slotProps={{
          toolbar: { setRows, setRowModesModel, rows, arrIds },
        }}
      />
    </Box>
  );
};

export default User;
