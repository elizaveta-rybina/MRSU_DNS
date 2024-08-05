<?php

class GlobalErrorHandler
{
    /**
     * Обработка исключений.
     *
     * Эта метод вызывается при возникновении исключения. Устанавливает код ответа HTTP 500
     * и возвращает информацию об исключении в формате JSON.
     *
     * @param Throwable $exception Исключение, которое нужно обработать.
     *
     * @return void
     */
    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);

        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ]);
    }

    /**
     * Обработка ошибок.
     *
     * Этот метод вызывается при возникновении ошибки в коде. Преобразует ошибку в исключение
     * для дальнейшей обработки в методе `handleException`.
     *
     * @param int $errno Уровень ошибки.
     * @param string $errstr Сообщение об ошибке.
     * @param string $errfile Файл, в котором произошла ошибка.
     * @param int $errline Номер строки, где произошла ошибка.
     *
     * @return bool Всегда возвращает false, чтобы PHP продолжал выполнение стандартной обработки ошибок.
     */
    public static function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): bool {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
